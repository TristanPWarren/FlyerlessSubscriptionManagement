<?php

namespace Flyerless\FlyerlessSubscriptionManagement\Http\Controllers\ParticipantApi;

use BristolSU\Support\Activity\Activity;
use BristolSU\Support\ModuleInstance\Connection\ModuleInstanceServiceRepository;
use BristolSU\Support\ModuleInstance\ModuleInstance;
//use Flyerless\FlyerlessClubManagement\Events\Description\DescriptionCreated;
//use Flyerless\FlyerlessClubManagement\Events\Description\DescriptionUpdated;
use Flyerless\FlyerlessSubscriptionManagement\Models\UserSocieties;
use Flyerless\FlyerlessSubscriptionManagement\Http\Controllers\Controller;
use BristolSU\Support\Authentication\Contracts\Authentication;
use BristolSU\Support\ActivityInstance\Contracts\ActivityInstanceResolver;
use BristolSU\ControlDB\Contracts\Repositories\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use function PHPUnit\Framework\exactly;


class UserSocietyController extends Controller
{

    public function index(Activity $activity, ModuleInstance $moduleInstance, Request $request, Authentication $authentication, User $userRepository) {

        $this->authorize('user-society.index');

        //Check if club description exists
        $userSocieties = UserSocieties::forResource()->first();

        //If not create new one and populate with default values
        if ($userSocieties === null) {
            $this->createBlankUserSocieties($authentication, $request, $userRepository);
            $userSocieties = UserSocieties::forResource()->first();
        }

        //Update user society preferences from flyerless
        $userSocietiesFromFlyerless = $this->getSocietiesFromFlyerless($moduleInstance, $userRepository, $authentication );

        if ($userSocietiesFromFlyerless['StatusCode'] === 200) {
            $newSocieties = array();

            foreach ($userSocietiesFromFlyerless['Clubs'] as &$club) {
                $tempSociety = array();
                $tempSociety['clubID'] = $club['ClubNo'];
                $tempSociety['clubName'] = $club['ClubName'];
                $tempSociety['interest'] = true;
                $tempSociety['email'] = ($club['MailingList'] === 1) ? true : 0;

                array_push($newSocieties, $tempSociety);
            }

            $userSocieties->societies = $newSocieties;

            $userSocieties->save();
        }

        $userSocieties = UserSocieties::forResource()->first();

        return $userSocieties;
    }




    public function store(Activity $activity, ModuleInstance $moduleInstance, Request $request, Authentication $authentication, User $userRepository)
    {
        $this->authorize('user-society.update');

        //Check if club description exists
        $userSocieties = UserSocieties::forResource()->first();

        //If not create new one and populate with default values
        if ($userSocieties === null) {
            $this->createBlankUserSocieties($authentication, $request, $userRepository);
            $userSocieties = UserSocieties::forResource()->first();
        }

        $updatedPreferences = json_decode((string) $request->input('societies'), false);

        $userSocieties->societies = $updatedPreferences;
        $userSocieties->save();


        //Update Flyerless
        $userSocieties = UserSocieties::forResource()->first();
        $oldPreferences = $userSocieties->societies;
        $connector = app(ModuleInstanceServiceRepository::class)->getConnectorForService('flyerless', $moduleInstance->id);

        $user = $userRepository->getById($authentication->getUser()->id());
        $userData = $user->data();
        $userEmail = $userData->email();
//        $userName = explode('@', $userEmail)[0];

        $newPreferences = array();


        foreach ($oldPreferences as $oldPreference) {
            $newPreference = $oldPreference;

            //Remove email from flyerless
            if ($oldPreference['email'] === false) {
                $body = [
                    'Request_Type' => 2,
                    'subType' => 2,
                    'studentEmail' => $userEmail,
                    'societyID' => $oldPreference['clubID'],
                ];
                $response = $connector->request('POST', '', $body);
                if (json_decode((string) $response->getBody()->getContents(), false)->Message === "Success") {
                    $newPreference['email'] = 0;
                }
            }

            //Remove interest from flyerless
            if ($oldPreference['interest'] === false) {
                $body = [
                    'Request_Type' => 2,
                    'subType' => 1,
                    'studentEmail' => $userEmail,
                    'societyID' => $oldPreference['clubID'],
                ];
                $response = $connector->request('POST', '', $body);
                if (json_decode((string) $response->getBody()->getContents(), false)->Message === "Success") {
                    continue;
                }
            }

            array_push($newPreferences, $newPreference);
        }

        $userSocieties = UserSocieties::forResource()->first();
        $userSocieties->societies = $newPreferences;
        $userSocieties->save();

        return $updatedPreferences;
    }


//    Helpers:
    private function createBlankUserSocieties(Authentication $authentication, Request $request, User $userRepository) {
        $user = $userRepository->getById($authentication->getUser()->id());
        $userData = $user->data();

        UserSocieties::create([
            'user_id' => $authentication->getUser()->id(),
            'user_name' => $userData->firstName(),
            'societies' => "",
            'activity_instance_id' => $request->input('activity_instance_id'),
        ]);
    }

    private function getSocietiesFromFlyerless(ModuleInstance $moduleInstance, User $userRepository, Authentication $authentication) {
        $connector = app(ModuleInstanceServiceRepository::class)->getConnectorForService('flyerless', $moduleInstance->id);

        $user = $userRepository->getById($authentication->getUser()->id());
        $userData = $user->data();
        $userEmail = $userData->email();
//        $userName = explode('@', $userEmail)[0];

        $body = [
            'Request_Type' => 1,
            'studentEmail' => $userEmail,
        ];

        $response = $connector->request('POST', '', $body);
        $response = json_decode((string) $response->getBody()->getContents(), true);

        return $response;
    }

}
