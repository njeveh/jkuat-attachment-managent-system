<div>
    <div class="wrapper">
        <!-- Sidebar  -->
        <x-attachee-side-nav-links />
        <!-- Page Content  -->
        <div id="content">
            <x-navbar />
            <main id="main-content" class="pb-5">
                <div class="page-title">
                    <h3>{{ $application->advert->studyArea->title }}/Ref: {{ $application->advert->reference_number }}</h3>
                </div>
                @if ($application->central_services_approval_status == 'canceled')
                    <div class="d-flex align-items-center justify-content-center">
                        <div class="toast show bg-warning">
                            <div class="toast-header bg-info">
                                Invalid application
                                <button type="button" class="btn-close" data-bs-dismiss="toast"></button>
                            </div>
                            <div class="toast-body">
                                You canceled this application.
                            </div>
                        </div>
                    </div>
                @endif
                <section class="">
                    <table class="table">
                        <thead>
                            <tr>
                                <th colspan='4' class="table-dark">
                                    <div class="d-flex justify-content-center align-items-center">
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
                                        <div class="btn-group btn-group-sm">
                                            @if ($application->central_services_approval_status == 'pending')
                                                @if ($application_accompaniment->status == 'pending_review' || $application_accompaniment->status == 'rejected')
                                                    <button class="btn btn-primary" data-bs-toggle="collapse"
                                                        data-bs-target="#update-form-{{ $application_accompaniment->id }}">Update</button>
                                                @endif
                                            @endif
                                            <a href="{{ Storage::url($application_accompaniment->path) }}"
                                                class="btn btn-success">View</a>
                                        </div>
                                    </td>
                                </tr>
                                @if ($application->central_services_approval_status == 'pending')
                                    @if ($application_accompaniment->name != 'application_letter' || $application_accompaniment->status == 'pending_review')
                                        <tr class="m-0 p-0">
                                            <td colspan='4' class="m-0 p-0">
                                                <form
                                                    wire:submit.prevent="updateUploads('{{ $application_accompaniment->name }}')">
                                                    <div wire:ignore.self
                                                        class="form-group mb-3 collapse bg-dark text-light p-2"
                                                        id="update-form-{{ $application_accompaniment->id }}">
                                                        <label for="{{ $application_accompaniment->id }}"
                                                            class="form-label">Update
                                                            {{ ucwords(preg_replace('/_/', ' ', $application_accompaniment->name)) }}</label>
                                                        <div class="input-group">
                                                            <input type="file"
                                                                wire:model="{{ $application_accompaniment->name }}"
                                                                class="form-control"
                                                                id="{{ $application_accompaniment->id }}" required>
                                                            <button type="submit" class="btn btn-info">Upload
                                                            </button>
                                                        </div>
                                                        @error($application_accompaniment->name)
                                                            <span class="error text-danger">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                        @enderror
                                                        <button type="button"
                                                            class="btn btn-outline-danger btn-block form-control my-2"
                                                            data-bs-toggle="collapse" aria-expanded="false"
                                                            data-bs-target="#update-form-{{ $application_accompaniment->id }}">
                                                            Close
                                                        </button>
                                                    </div>
                                                </form>
                                            </td>
                                        </tr>
                                    @endif
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </section>
                <section class="mt-4">
                    <form class="mt-4 needs-validation" wire:submit.prevent="updateAttachmentPeriod">
                        @csrf
                        <div class="mb-3">
                            <div class='w-100 d-flex justify-content-center align-items-center'>
                                <h3>Attachment Period</h3>
                            </div>
                            <div class="form-group mb-3">
                                <label for="attachment-start-date" class="form-label">{{ __('Date your attachment period begins.') }} <span class="text-danger">*</span></label>
                                <input type="date" wire:model="attachment_start_date" class="form-control" id="attachment-start-date">
                                @error('attachment_start_date')
                                <span class="text-danger">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="form-group mb-3">
                                <label for="minimum-attachment-weeks" class="form-label">{{ __('Minimum number of weeks you are supposed to be on attachment.') }} <span class="text-danger">*</span></label>
                                <input type="number" min="1" max="12" wire:model="minimum_attachment_weeks" class="form-control" id="minimum-attachment-weeks">
                                @error('minimum_attachment_weeks')
                                <span class="text-danger">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="form-group mb-3">
                                <label for="attachment-end-date" class="form-label">{{ __('Date your attachment period ends.') }} <span class="text-danger">*</span></label>
                                <input type="date" wire:model="attachment_end_date" class="form-control" id="attachment-end-date">
                                @error('attachment_end_date')
                                <span class="text-danger">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="w-100 container text-right mb-3">
                            <button type="submit" class="w-100 btn btn-primary">{{ __('Update Attachment Period') }}</button>
                        </div>
                    </form>
                </section>

                @if ($application->central_services_approval_status == 'pending')
                    <div class="w-100 d-flex justify-content-center align-items-center m-2">
                        <button type="button" wire:click="warn('cancel')"
                            class="w-100 btn btn-danger">{{ __('cancel Application') }}</button>
                    </div>
                @endif
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
            <button wire:click="$emit('{{ $confirmed_action }}')" type="button" data-bs-dismiss="modal"
                data-bs-target="#promptModal" class="btn btn-danger">Yes</button>
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
