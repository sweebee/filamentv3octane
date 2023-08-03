<?php

namespace App\Observers;

use App\Models\Supporter;
use Illuminate\Support\Facades\Http;

class SupporterObserver
{
    public function saved(Supporter $supporter)
    {
        $key = '4aPL3FXRZrnN70XO4XtfL0biLnEw0hySqXAWUWzp8rbegv8uQZShtz5OU30Qex1G';

        $data = [
            'email' => $supporter->email,
            'first_name' => $supporter->first_name,
            'last_name' => $supporter->last_name,
            'groups' => $supporter->group_id ? [$supporter->group->name] : [],
        ];

        if($supporter->mijnetickets_id){
            Http::withHeaders(['authorization' => 'Bearer ' . $key])->post('https://api.mijnetickets.nl/manage/organisation/customers/'.$supporter->mijnetickets_id, $data);
        } else {
           $response = Http::withHeaders(['authorization' => 'Bearer ' . $key])->post('https://api.mijnetickets.nl/manage/organisation/customers', $data)->json();
           $supporter->mijnetickets_id = $response['data']['id'];
           $supporter->saveQuietly();
        }
    }
}
