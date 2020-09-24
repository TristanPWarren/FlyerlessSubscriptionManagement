<?php

namespace Flyerless\FlyerlessSubscriptionManagement\Http\Controllers\AdminApi;

use BristolSU\Support\Activity\Activity;
use BristolSU\Support\ModuleInstance\Connection\ModuleInstanceServiceRepository;
use BristolSU\Support\ModuleInstance\ModuleInstance;
//use Flyerless\FlyerlessClubManagement\Events\Description\DescriptionCreated;
//use Flyerless\FlyerlessClubManagement\Events\Description\DescriptionUpdated;
use Flyerless\FlyerlessSubscriptionManagement\Http\Controllers\Controller;
use BristolSU\Support\Authentication\Contracts\Authentication;
use BristolSU\Support\ActivityInstance\Contracts\ActivityInstanceResolver;
use BristolSU\ControlDB\Contracts\Repositories\User;
use BristolSU\ControlDB\Contracts\Repositories\Group;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use function PHPUnit\Framework\exactly;
use Carbon\Carbon;


class MemberDetailsController extends Controller
{
    public function index(Activity $activity, ModuleInstance $moduleInstance, Authentication $authentication) {
        $this->authorize('download-members.download');

        $connector = app(ModuleInstanceServiceRepository::class)->getConnectorForService('flyerless', $moduleInstance->id);
        $body = [
            'Request_Type' => 3,
            'societyID' => $authentication->getGroup()->id(),
        ];

        $response = $connector->request('POST', '', $body);
        $response = json_decode((string) $response->getBody()->getContents(), true);



        $date = Carbon::now()->toDateString();
        $headers = [
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Content-type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename=members_' . $date . '.csv',
            'Expires' => '0',
            'Pragma' => 'public',
        ];

        $dataResponse = $response['Students'];
        $data = array();
        array_unshift($data, array_keys($dataResponse[0]));
        foreach ($dataResponse as $row) {
            $temp = array();
            array_push($temp, $row['Name']);
            array_push($temp, $row['Email']);
            array_push($data, $temp);
        }

        return $data;


//        $callback = function() use ($data) {
//            $file = fopen('php://output', 'w');
//
//            foreach ($data as $row) {
//                fputcsv($file, $row);
//            }
//            fclose($file);
//        };
//
//        return $response()->stream($callback, 200, $headers);

    }

}
