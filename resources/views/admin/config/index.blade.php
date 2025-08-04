@extends('admin.contentNavLayout')

@section('title', 'Cấu hình')

@section('page-script')
    <script src="{{ asset('assets/admin/js/pages-account-settings-account.js') }}"></script>
@endsection


@section('content')




    <div class="d-flex mb-3 justify-content-between action-save">
        <h4 class="py-3 mb-0 ps-3">
            <span class="text-muted fw-light"></span> Cấu hình
        </h4>
        <button form="formAccountSettings" id="submit1" type="button" class="btn btn-primary me-3">Lưu</button>
    </div>
    <div class="card">
        <div class="card-body">
            <form id="formAccountSettings" action="{{ route('admin.config.save') }}" method="POST">
                <div class="demo-inline-spacing mt-3">
                    <div class="list-group list-group-horizontal-md text-md-center" role="tablist">
                        <a class="list-group-item list-group-item-action waves-effect active" id="home-list-item"
                            data-bs-toggle="list" href="#horizontal-home" aria-selected="true" role="tab">
                            <i class="menu-icon tf-icons mdi mdi-cog-outline"></i>

                            Chung</a>
                        <a class="list-group-item list-group-item-action waves-effect" id="profile-list-item"
                            data-bs-toggle="list" href="#horizontal-profile" aria-selected="false" role="tab"
                            tabindex="-1">
                            <i class="menu-icon tf-icons mdi mdi-content-paste"></i>

                            Nội dung</a>
                        <a class="list-group-item list-group-item-action waves-effect" id="messages-list-item"
                            data-bs-toggle="list" href="#horizontal-messages" aria-selected="false" role="tab"
                            tabindex="-1">
                            <i class="menu-icon tf-icons mdi mdi-xml"></i>
                            Script</a>
                    </div>
                    <div class="tab-content px-0 mt-0">
                        <div class="tab-pane fade active show" id="horizontal-home" role="tabpanel"
                            aria-labelledby="home-list-item">
                            @include('admin.config.common')
                        </div>
                        <div class="tab-pane fade" id="horizontal-profile" role="tabpanel"
                            aria-labelledby="profile-list-item">
                            @include('admin.config.editor')
                        </div>
                        <div class="tab-pane fade" id="horizontal-messages" role="tabpanel"
                            aria-labelledby="messages-list-item">
                            @include('admin.config.script')
                        </div>
                    </div>
                </div>
                @csrf

            </form>
        </div>
    </div>
@endsection

@section('script_page')

@endsection
