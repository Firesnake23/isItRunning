namespace firesnake\isItRunning\controllers;
use firesnake\isItRunning\entities\EnvironmentResult;
use firesnake\isItRunning\events\RequestEvent;
use firesnake\isItRunning\http\RedirectResponse;
use firesnake\isItRunning\http\TextResponse;
use firesnake\isItRunning\http\TwigResponse;
use firesnake\isItRunning\IsItRunning;

class ResultController
{
    public function overview(RequestEvent $event)
    {
        $request = $event->getRequest();
        if (!isset($request->getGet()['q'])) {
            return new RedirectResponse('/dashboard');
        }

        /** @var IsItRunning $isItRunning */
        $isItRunning = $event->getParam('isItRunning');
        $em = $isItRunning->getEntityManager();

        /** @var EnvironmentResult[] $envResults */
        $envResults = $em->getRepository(EnvironmentResult::class)->findBy([
            'checkableEnvironment' => $request->getGet()['q']
        ]);

        $envResults = array_reverse($envResults);

        $statusMap = [];
        foreach ($envResults as $envResult) {
            $checkResults = $envResult->getCheckResults();
            $status = true;
            foreach($checkResults as $checkResult) {
                $status = $status & $checkResult->isPassed();
            }
            $statusMap[$envResult->getId()] = $status;
        }

        return new TwigResponse('/result/overview.html.twig', [
            'results' => $envResults,
            'statusMap' => $statusMap,
            'authenticatedUser' => $isItRunning->getAuthenticatedUser()
        ]);
    }
