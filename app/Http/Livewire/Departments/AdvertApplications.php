<?php

namespace App\Http\Livewire\Departments;

use App\Models\Advert;
use App\Models\Application;
use App\Utilities\Utilities;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithPagination;

class AdvertApplications extends Component
{

    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $advert;
    public $table_title;
    public $table_title_bg_color;
    public $next_quarter;
    public $tab_filter;
    public $status_filter;
    public $search = '';
    public $feedback;
    public $alert_class;
    public $feedback_header;
    public $confirmed_action;
    public $confirmed_action_parameter;


    protected $listeners = [
        'deleteApplication' => 'deleteApplication',
        'rejectApplication' => 'rejectApplication',
        'acceptApplication' => 'acceptApplication',
        //'revokeApplicationAcceptance' => 'revokeApplicationAcceptance',
    ];

    public function mount($id)
    {
        $this->table_title = 'All Applications';
        $this->table_title_bg_color = 'bg-primary';
        $this->status_filter = '';
        $this->next_quarter = Utilities::get_next_quarter_data()['quarter'];
        $this->advert = Advert::find($id);

    }
    public function render()
    {
        $applications = Application::where('advert_id', $this->advert->id)->whereLike(['applicant.first_name', 'applicant.second_name', 'advert.year', 'central_services_approval_status',], $this->search ?? '')
            ->when($this->status_filter, function ($query, $status) {
                return $query->where('central_services_approval_status', $status);
            })->latest()->paginate(10);
        return view('livewire.departments.advert-applications', ['applications' => $applications]);
    }

    public function updatedStatusFilter()
    {
        switch ($this->status_filter) {
            case '':
                $this->table_title = 'All Applications';
                $this->table_title_bg_color = 'bg-primary';
                break;

            case 'pending':
                $this->table_title = 'Pending Applications';
                $this->table_title_bg_color = 'bg-info';
                break;

            case 'accepted':
                $this->table_title = 'Accepted Applications';
                $this->table_title_bg_color = 'bg-success';
                break;
            case 'rejected':
                $this->table_title = 'Rejected Applications';
                $this->table_title_bg_color = 'bg-danger';
                break;
            case 'canceled':
                $this->table_title = 'Canceled Applications';
                $this->table_title_bg_color = 'bg-warning';
                break;
            case 'revoked':
                $this->table_title = 'Revoked Applications';
                $this->table_title_bg_color = 'bg-danger';
                break;
        }
    }

    public function resetAllFilters()
    {
        $this->table_title = 'All Applications';
        $this->table_title_bg_color = 'bg-primary';
        $this->status_filter = '';
        $this->tab_filter = 'active';
        $this->search = '';
    }

    public function resetToTabDefault()
    {
        $this->table_title = 'All Applications';
        $this->table_title_bg_color = 'bg-primary';
        $this->status_filter = '';
        $this->search = '';
    }

    public function rejectApplication($id)
    {
        try {
            $application = Application::find($id);

            Application::where('id', $id)->update(
                [
                    'department_approval_status' => 'rejected',
                ]
            );
            $applicant = $application->applicant;
            if ($applicant->engagement_level < 2) {
                $applicant->engagement_level = 2;
                $applicant->save();
            }
            $application->refresh();
        } catch (\Exception $e) {
            $this->feedback_header = 'Error performing requested action!!';
            $this->feedback = 'Something went wrong. Please try again and if the error persists contact support team to resolve the issue';
            $this->alert_class = 'alert-danger';
            $this->dispatchBrowserEvent('action_feedback');
        }

        $this->feedback_header = 'Success!!';
        $this->feedback = 'Application rejected.';
        $this->alert_class = 'alert-success';
        $this->dispatchBrowserEvent('action_feedback');

    }

    public function approveApplication($id)
    {
        try {
            $application = Application::find($id);

            Application::where('id', $id)->update(
                [
                    'department_approval_status' => 'approved',
                ]
            );
            $applicant = $application->applicant;
            if ($applicant->engagement_level < 2) {
                $applicant->engagement_level = 2;
                $applicant->save();
            }
            $application->refresh();
        } catch (\Exception $e) {
            $this->feedback_header = 'Error performing requested action!!';
            $this->feedback = 'Something went wrong. Please try again and if the error persists contact support team to resolve the issue';
            $this->alert_class = 'alert-danger';
            $this->dispatchBrowserEvent('action_feedback');
        }

        $this->feedback_header = 'Success!!';
        $this->feedback = 'Application approved.';
        $this->alert_class = 'alert-success';
        $this->dispatchBrowserEvent('action_feedback');

    }

}