@include('admin_panel.layouts.header')
    <div class="page-container animsition">
        <div id="dashboardPage">
            <!-- Main Menu -->
            @include('admin_panel.layouts.top_bar')
            <!-- Main Menu -->
            @include('admin_panel.layouts.side_panel')
            <form action="{{url('admin_panel/manage/settings')}}" method="post" id="general_form">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <main>
                    <div class="page-breadcrumb">
                        <div class="row">
                            <div class="col-12">
                                <h4 class="page-title">Manage Settings</h4>
                            </div>
                        </div>
                    </div>
                    @include('admin_panel.layouts.message')
                    <div class="container-fluid card-commission-section  parent-details-section">
                        <div class="row account-form">
                            <div class="col-12 col-sm-12 pb-3">
                                <label>Privacy Policy</label>
                                <textarea class="form-control @error('sPrivacy') is-invalid @enderror" name="sPrivacy" id="description" required>{{ old('sPrivacy', $StngDtl['sPrivacy']) }}</textarea>
                             </div> 
                        </div>
                    </div>
                    <div class="container-fluid card-commission-section parent-list-section parent-details-section">
                        <div class="row  account-form">
                            <div class="col-12 col-sm-12 pb-3">
                                <label>Terms & Conditions</label>
                                <textarea class="form-control @error('sTermCond') is-invalid @enderror" name="sTermCond" id="description1" required >{{ old('sTermCond', $StngDtl['sTerm_Cond']) }}</textarea>
                             </div> 
                        </div>
                    </div>
                    <div class="container-fluid card-commission-section parent-list-section parent-details-section">
                        <div class="row  account-form">
                            <div class="col-12 col-sm-12 pb-3">
                                <label>Service Provider Terms & Conditions</label>
                                <textarea class="form-control @error('sSerProvTerms') is-invalid @enderror" name="sSerProvTerms" id="description2" required >{{ old('sSerProvTerms', $StngDtl['sSerProv_Terms']) }}</textarea>
                             </div> 
                        </div>
                        <div class="row">
                            <div class="col-lg-12 services-btns">
                                <ul class="m-auto text-center pt-4 pb-4">
                                    <li>
                                        <div class="add-btn  mt-0"><button type="button" title="Back" class="mt-0" onclick="history.back()">Back</button></div>
                                    </li>
                                    <li>
                                        <div class="add-btn  mt-0 mtautomedia364"><button title="Save" name="submit" type="submit" class="mt-0">Save</button></div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </main>
            </form>
        </div>
    </div>
    <script>
    CKEDITOR.replace( 'description' );
    CKEDITOR.replace( 'description1' );
    CKEDITOR.replace( 'description2' );
    </script>
@include('admin_panel.layouts.footer')