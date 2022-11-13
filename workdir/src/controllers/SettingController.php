<?php

namespace firesnake\isItRunning\controllers;

use firesnake\isItRunning\entities\User;
use firesnake\isItRunning\entities\UserSettings;
use firesnake\isItRunning\events\RequestEvent;
use firesnake\isItRunning\http\RedirectResponse;
use firesnake\isItRunning\http\Response;
use firesnake\isItRunning\http\TwigResponse;
use firesnake\isItRunning\IsItRunning;

class SettingController
{
    public function overview(RequestEvent $requestEvent): Response
    {
        $authenticatedUser = $this->getAuthenticatedUser($requestEvent);

        $mail = $this->getSettingValue($authenticatedUser, UserSettings::KEY_MAIL, null);
        $cleanup = $this->getSettingValue($authenticatedUser, UserSettings::KEY_CLEANUP, 'HOURLY');


        return new TwigResponse('settings/overview.html.twig', [
            'authenticatedUser' => $authenticatedUser,
            'mail' => $mail,
            'cleanup' => $cleanup,
            'cleanupValues' => UserSettings::VALUES_CLEANUP
        ]);
    }

    public function changeMail(RequestEvent $event): Response {
        $authenticatedUser = $this->getAuthenticatedUser($event);

        if($authenticatedUser == null) {
            return new RedirectResponse('/');
        }

        if(isset($event->getRequest()->getPost()['mail'])) {
            $mail = $event->getRequest()->getPost()['mail'];

            $mailSetting = $this->getSetting($authenticatedUser, UserSettings::KEY_MAIL);

            $mailSetting->setValue($mail);

            /** @var IsItRunning $isItRunning */
            $isItRunning = $event->getParam('isItRunning');
            $userManager = $isItRunning->getUserManager();

            $userManager->saveUser($authenticatedUser);

            $isItRunning->getEntityManager()->persist($mailSetting);
            $isItRunning->getEntityManager()->flush();;
        }

        return new RedirectResponse('/settings');
    }

    public function changePassword(RequestEvent $event) : Response
    {
        $authenticatedUser = $this->getAuthenticatedUser($event);
        if($authenticatedUser == null) {
            return new RedirectResponse('/');
        }

        $request = $event->getRequest();
        if(
            isset($request->getPost()['oldPass']) &&
            isset($request->getPost()['newPass']) &&
            isset($request->getPost()['newPass2'])
        ) {
            $oldPass = $request->getPost()['oldPass'];
            $newPass = $request->getPost()['newPass'];
            $newPass2 = $request->getPost()['newPass2'];

            if($newPass == $newPass2) {
                /** @var IsItRunning $isItRunning */
                $isItRunning = $event->getParam('isItRunning');
                $userService = $isItRunning->getUserManager();
                $userService->changePassword($authenticatedUser, $oldPass, $newPass);
            }
        }

        return new RedirectResponse('/settings');
    }

    public function changeCleanupInterval(RequestEvent $event) {
        $authenticatedUser = $this->getAuthenticatedUser($event);
        if($authenticatedUser == null) {
            return new RedirectResponse('/');
        }

        $request = $event->getRequest();
        if (isset($request->getPost()['intervall'])) {
            $intervall = $request->getPost()['intervall'];
            $setting = $this->getSetting($authenticatedUser, UserSettings::KEY_CLEANUP);

            if(in_array($intervall, UserSettings::VALUES_CLEANUP)) {
                $setting->setValue($intervall);

                /** @var IsItRunning $isItRunning */
                $isItRunning = $event->getParam('isItRunning');
                $isItRunning->getEntityManager()->persist($setting);
                $isItRunning->getEntityManager()->flush();;
            }
        }

        return new RedirectResponse('/settings');
    }

    private function getAuthenticatedUser(RequestEvent $event) :?User
    {
        /** @var IsItRunning $isItRunning */
        $isItRunning = $event->getParam('isItRunning');
        return $isItRunning->getAuthenticatedUser();
    }

    private function getSettingValue(User $user, string $key, ?string $default = null) {
        $setting = $user->getSetting($key);
        if($setting == null) {
            return $default;
        }

        return $setting->getValue();
    }

    private function getSetting(User $user, string $key) :UserSettings
    {
        $setting = $user->getSetting($key);

        if($setting == null) {
            $setting = new UserSettings();
            $setting->setUser($user);
            $setting->setKey($key);
        }
        return $setting;
    }
}