<?php

namespace App\Http\Livewire\CentralServices;

use App\Http\Livewire\BaseApplications;
use App\Models\Application;

class ApplicationDetails extends BaseApplications
{
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
        return view('livewire.central-services.application-details', ['application' => $this->application,], );
    }

    public function setIntendedStatus($status, $action)
    {
        $this->intended_status = $status;
        $this->intended_action = $action;
    }
}
