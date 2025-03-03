<div>
    <x-slot:title>
        {{ __("Applicant's Application Documents") }}
    </x-slot:title>
    <div class="wrapper">
        <!-- Sidebar  -->
        <x-department-admin-side-nav-links />
        <!-- Page Content  -->
        <div id="content">
            <x-navbar />
            <div class="fixed-filters bg-light">
                <ul class="nav nav-tabs nav-justified">
                    <li class="nav-item">
                        <a href={{ route('departments.view_application_details', $application->id) }}
                            class="nav-link active bg-secondary text-light">Application Documents</a>
                    </li>
                    <li class="nav-item">
                        <a href={{ route('departments.view_applicant_biodata', $application->id) }}
                            class="nav-link">Biodata</a>
                    </li>
                    <li class="nav-item">
                         <a href={{ route('departments.view_applicant_profile', $application->id) }}
                            class="nav-link">Profile</a>
                    </li>
                </ul>
            <div id="sticky-btns-department-view-application-docs"
                class="d-flex align-items-center justify-content-between p-3">

                @switch($application->department_approval_status)
                    @case('pending')
                        <div class="d-flex align-items-center justify-content-start">Application Status :&nbsp;
                            <span class="text-info">Pending</span>
                        </div>
                        <div class="d-flex flex-wrap justify-content-end">
                            <button class="btn btn-primary m-2" wire:click="approveApplication('{{ $application->id }}')">
                                Approve
                            </button>
                            <button wire:click="rejectApplication('{{ $application->id }}')"
                                class="btn btn-danger m-2">Reject</button>
                        </div>
                    @break

                    @case('rejected')
                        <div class="d-flex align-items-center justify-content-start">Application Status :&nbsp;
                            <span class="text-danger">Rejected</span>
                        </div>
                        <div class="d-flex flex-wrap justify-content-end">
                            <button class="btn btn-primary m-2" wire:click="approveApplication('{{ $application->id }}')">
                                Approve
                            </button>
                        </div>
                    @break

                    @case('approved')
                        <div class="d-flex align-items-center justify-content-start">Application Status :&nbsp;
                            <span class="text-success">Approved</span>
                        </div>
                        <div class="d-flex flex-wrap justify-content-end">
                            <button wire:click="rejectApplication('{{ $application->id }}')" class="btn btn-danger m-2">Reject</button>
                        </div>
                    @break
                @endswitch
            </div>
            </div>
            <main id="main-content" class="pb-5">
                <div class="page-title">
                    <h3>{{ $application->advert->studyArea->title }}/ Ref: {{ $application->advert->reference_number }}
                    </h3>
                </div>
                <div class="page-title">
                    <h3>Applicant's Name: {{ $application->applicant->first_name }}
                        {{ $application->applicant->second_name }}</h3>
                    <div>Attachment period starting date: {{ $application->attachment_start_date }}</div>
                    <div>Minimum attachment weeks: {{ $application->minimum_attachment_weeks }}</div>
                    <div>Attachment period ending date: {{ $application->attachment_end_date }}</div>
                </div>
                <section class="">
                    <table class="table">
                        <thead>
                            <tr>
                                <th colspan='4' class="table-dark">
                                    <div class="d-flex justify-content-center">
                                        Uploaded Application Documents
                                    </div>
                                </th>
                            <tr>
                            <tr>
                                <th>Document</th>
                                <th>Status</th>
                                <th>Review Remarks</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($application_accompaniments as $application_accompaniment)
                                <tr>
                                    <td>{{ ucwords(preg_replace('/_/', ' ', $application_accompaniment->name)) }}</td>
                                    <td>
                                        @switch($application_accompaniment->status)
                                            @case('pending_review')
                                                <span class="text-info">Pending review</span>
                                            @break

                                            @case('rejected')
                                                <span class="text-danger">Rejected</span>
                                            @break

                                            @case('accepted')
                                                <span class="text-success">Accepted</span>
                                            @break

                                            @default
                                                <span class="text-warning">Missing</span>
                                        @endswitch
                                    </td>
                                    <td>{{ $application_accompaniment->review_remarks }}</td>
                                    <td>
                                        <button type='button' class="btn btn-success">
                                            <a href="{{ Storage::url($application_accompaniment->path) }}">View</a>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </section>
            </main>
            <x-footer />
        </div>
    </div>
    <x-prompt-modal>
        <x-slot:title>
            {{ $feedback_header }}
        </x-slot:title>
        <x-slot:body class="{{ $alert_class }}">
            {{ $feedback }}
        </x-slot:body>
        <x-slot:confirm_btn>
            <button wire:click="$emit('{{ $confirmed_action }}', '{{ $confirmed_action_parameter }}')"
                type="button" data-bs-dismiss="modal" data-bs-target="#promptModal"
                class="btn btn-danger">Yes</button>
        </x-slot:confirm_btn>
    </x-prompt-modal>

    <x-notification-modal>
        <x-slot:title>
            {{ $feedback_header }}
        </x-slot:title>
        <x-slot:body class="{{ $alert_class }}">
            {{ $feedback }}
        </x-slot:body>
    </x-notification-modal>

    <script>
        window.addEventListener('action_confirm', (event) => {
            $("#prompt-modal-btn").click();
        })
        window.addEventListener('action_feedback', (event) => {
            $("#notification-modal-btn").click();
        })
    </script>
</div>
