<x-app-layout>
    <div class="wrapper">
        <!-- Sidebar  -->
        <x-department-admin-side-nav-links />
        <!-- Page Content  -->
        <div id="content">
            <x-navbar />
            <div id="main-content">
                <div class="page-title">
                    <h3>Create New Study Area</h3>
                </div>
                @if (session('error'))
                    <div class="alert alert-danger py-1" role="alert">
                        {{ session('error') }}
                    </div>
                @endif
                <section>
                    @livewire('departments.create-new-study-area')
                </section>
            </div>

            <x-footer />
        </div>
    </div>
</x-app-layout>
