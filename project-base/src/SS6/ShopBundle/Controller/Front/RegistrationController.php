<?php

namespace SS6\ShopBundle\Controller\Front;

use SS6\ShopBundle\Component\Controller\FrontBaseController;
use SS6\ShopBundle\Component\Domain\Domain;
use SS6\ShopBundle\Form\Front\Registration\RegistrationFormType;
use SS6\ShopBundle\Model\Customer\CustomerEditFacade;
use SS6\ShopBundle\Model\Customer\User;
use SS6\ShopBundle\Model\Customer\UserDataFactory;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

class RegistrationController extends FrontBaseController {

	/**
	 * @var \SS6\ShopBundle\Model\Customer\CustomerEditFacade
	 */
	private $customerEditFacade;

	/**
	 * @var \SS6\ShopBundle\Model\Customer\UserDataFactory
	 */
	private $userDataFactory;

	/**
	 * @var \SS6\ShopBundle\Component\Domain\Domain
	 */
	private $domain;

	public function __construct(
		Domain $domain,
		UserDataFactory $userDataFactory,
		CustomerEditFacade $customerEditFacade
	) {
		$this->domain = $domain;
		$this->userDataFactory = $userDataFactory;
		$this->customerEditFacade = $customerEditFacade;
	}

	public function registerAction(Request $request) {
		$form = $this->createForm(new RegistrationFormType());

		$userData = $this->userDataFactory->createDefault($this->domain->getId());

		$form->setData($userData);
		$form->handleRequest($request);

		if ($form->isValid()) {
			$userData = $form->getData();
			$userData->domainId = $this->domain->getId();

			try {
				$user = $this->customerEditFacade->register($userData);

				$this->login($user);

				$this->getFlashMessageSender()->addSuccessFlash(t('Byli jste úspěšně zaregistrováni'));

				return $this->redirectToRoute('front_homepage');
			} catch (\SS6\ShopBundle\Model\Customer\Exception\DuplicateEmailException $e) {
				$form->get('email')->addError(new FormError(t('V databázi se již nachází zákazník s tímto e-mailem')));
			}
		}

		if ($form->isSubmitted() && !$form->isValid()) {
			$this->getFlashMessageSender()->addErrorFlash(t('Prosím zkontrolujte si správnost vyplnění všech údajů'));
		}

		return $this->render('@SS6Shop/Front/Content/Registration/register.html.twig', [
			'form' => $form->createView(),
		]);
	}

	/**
	 * @param \SS6\ShopBundle\Model\Customer\User $user
	 */
	private function login(User $user) {
		$token = new UsernamePasswordToken($user, $user->getPassword(), 'frontend', $user->getRoles());
		$this->get('security.token_storage')->setToken($token);

		// dispatch the login event
		$request = $this->get('request');
		$event = new InteractiveLoginEvent($request, $token);
		$this->get('event_dispatcher')->dispatch('security.interactive_login', $event);
	}

}
