<?php

namespace App\Http\Livewire\Departments;


use App\Models\Application;
use App\Models\ApplicationAccompaniment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use App\Http\Livewire\Departments\AdvertApplications;

class ViewApplicationDetails extends AdvertApplications
{

    public $feedback;
    public $alert_class;
    public $feedback_header;
    public $confirmed_action;
    public $advert;
    public $application;
    public $application_accompaniments;
    public $intended_status;
    public $intended_action;
    public $review_remarks;
    public $uploads_tab_class;
    public $biodata_tab_class;
    public $profile_tab_class;
    public $application_id;

    public function mount($id)
    {
        $this->application_id = $id;
        $this->uploads_tab_class = 'active bg-secondary text-light';
        $this->biodata_tab_class = 'text-dark';
        $this->profile_tab_class = 'text-dark';

    }
    public function render()
    {
        $this->application = Application::find($this->application_id);
        $this->application_accompaniments = $this->application->applicationAccompaniments;
        return view('livewire.departments.view-application-details', ['application' => $this->application,], );
    }
}