<div class="wrapper">
    <!-- Sidebar  -->
    <x-central-services-side-nav-links />
    <!-- Page Content  -->
    <div id="content">
        <x-navbar />
        <div id="main-content">
            <section class="pb-4">
                <div class="page-title">
                    <h2>{{ $advert->reference_number }}</h2>
                </div>
                <div class="advert-details">
                    <div class="mt-2" style="margin-bottom: 30px;">
                        <div class="d-flex justify-content-end">
                            <a href="/central-services/edit-advert/{{ $advert->id }}"
                                class=" btn btn-primary action-button">{{ __('Edit Advert') }}</a>
                        </div>
                        <div
                            style="display: flex; flex-wrap: wrap; flex-direction:row; align-items:center; margin:0 0 20px 20px;">
                            <img src="/assets/static/logo.png" alt="Logo"
                                style="height: 40px; width:40px; margin-right:20px;">
                            <h4>{{ $advert->studyArea->title }}</h4>
                        </div>
                    </div>
                    <div style="margin-bottom: 20px;">
                        <div class='mb-3'>
                            <h5>Year: {{ $advert->year }}</h5>
                        </div>

                        <div class='mb-3'>
                            <h5>Vacancies:</h5>
                        </div>
                        <section class="overflow-auto">
                            <table class="table table-hover table-responsive-sm mb-3">
                                <thead>
                                    <tr class="bg-dark text-light">
                                        <th scope="col">Quarter.</th>
                                        <th scope="col">Declared Vacancies</th>
                                        <th scope="col">Occupied</th>
                                        <th scope="col">Vacant</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Quarter 1 (Jul-Sept)</td>
                                        <td>{{ $advert->quarter1_vacancies }}</td>
                                        <td>{{ $advert->attachees->where('applicant.engagement_level', 5)->where('quarter', 1)->count() }}
                                        </td>
                                        <td>{{ $advert->quarter1_vacancies -$advert->attachees->where('applicant.engagement_level', 5)->where('quarter', 1)->count() }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Quarter 2 (Oct-Dec)</td>
                                        <td>{{ $advert->quarter2_vacancies }}</td>
                                        <td>{{ $advert->attachees->where('applicant.engagement_level', 5)->where('quarter', 2)->count() }}
                                        </td>
                                        <td>{{ $advert->quarter2_vacancies -$advert->attachees->where('applicant.engagement_level', 5)->where('quarter', 2)->count() }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Quarter 3 (Jan-Mar)</td>
                                        <td>{{ $advert->quarter3_vacancies }}</td>
                                        <td>{{ $advert->attachees->where('applicant.engagement_level', 5)->where('quarter', 3)->count() }}
                                        </td>
                                        <td>{{ $advert->quarter3_vacancies -$advert->attachees->where('applicant.engagement_level', 5)->where('quarter', 3)->count() }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Quarter 4 (Apr-Jun)</td>
                                        <td>{{ $advert->quarter4_vacancies }}</td>
                                        <td>{{ $advert->attachees->where('applicant.engagement_level', 5)->where('quarter', 4)->count() }}
                                        </td>
                                        <td>{{ $advert->quarter4_vacancies -$advert->attachees->where('applicant.engagement_level', 5)->where('quarter', 4)->count() }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </section>
                    </div>

                    <div>
                        <h5>Description : </h5>
                        <div class="advert-view">
                            <div>{{ $advert->description }}</div>
                            @if(count($requirements) > 0)
                                <h5 class="mt-4">Requirements:</h5>
                                <ul>
                                    @foreach($requirements as $requirement)
                                        <li>{{ $requirement->value }}</li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    </div>
                    <div class="d-flex justify-content-end align-content-center m-2 pe-2">
                        @if($advert->is_active)
                            <button wire:click="warn('deactivate')" class="m-2 btn action-button btn-danger"
                                style="border: none;">Deactivate</button>
                        @else
                            <button wire:click="warn('activate')" class="m-2 btn action-button btn-primary"
                                style="border: none;">Activate</button>
                        @endif
                        @if($advert->approval_status == 'pending approval')

                            <button wire:click="warn('approve')" class="m-2 btn action-button btn-success"
                                style="border: none;">Approve</button>
                        @elseif($advert->approval_status == 'approved')
                            <button wire:click="warn('disapprove')" class="m-2 btn action-button btn-danger"
                                style="border: none;">Disapprove</button>
                        @elseif($advert->approval_status == 'disapproved')
                            <button wire:click="warn('approve')" class="m-2 btn action-button btn-success"
                                style="border: none;">Approve</button>
                        @endif
                        <button wire:click="warn('delete')" class="m-2 btn action-button btn-danger"
                            style="border: none;">{{ __('Delete') }}
                        </button>
                    </div>
                </div>
            </section>


        </div>
        <x-footer />
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
        window.addEventListener('advert_action_confirm', (event) => {
            $("#prompt-modal-btn").click();
        })
        window.addEventListener('advert_action_feedback', (event) => {
            $("#notification-modal-btn").click();
        })

    </script>
</div>
