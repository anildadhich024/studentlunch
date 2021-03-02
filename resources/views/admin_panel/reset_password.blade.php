<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{$sTitle}} | Student Lunch</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" media="all" href="../assets/css/style.css"> 
</head>
<body>

    <section id="loginscreen" class="w-100 h-100">
        <div class="container-fluid">
            <div class="row pt20 m-auto">
                <div class="col-12"> 
                    <div class="loginbox m-auto">
                        @include('admin_panel.layouts.message')
                        <div class="d-flex justify-content-start flex-row">
                            <div class="loginicon">
                                <img src="../assets/images/man-is-working-with-laptop_28923-38.png" alt="" class="img-fluid">
                            </div>
                            <div class="loginheading">
                                Reset Password <br>
                                Students Lunch
                            </div>
                        </div>
                        <div class="text-center pt-2 pb-4">
                            Provide new password
                        </div>
                        <div class="loginform px-4">
                            <form autocomplete="off" method="post" action="{{url('admin_panel/reset_password/save')}}" id="general_form">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">New Password</label>
                                    <input type="password" class="form-control @error('sLgnPass') is-invalid @enderror" name="sLgnPass" placeholder="New Password" autocomplete="off" required value="{{old('sLgnPass')}}" onkeypress="return LenCheck(event, this.value, '16')"> 
                                    @error('sLgnPass') <div class="invalid-feedback"><span>{{$errors->first('sLgnPass')}}</span></div>@enderror
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Confirm Password</label>
                                    <input type="password" class="form-control @error('sCnfrmPass') is-invalid @enderror" name="sCnfrmPass" placeholder="Confirm Password" autocomplete="off" required value="{{old('sCnfrmPass')}}" onkeypress="return LenCheck(event, this.value, '16')"> 
                                    @error('sCnfrmPass') <div class="invalid-feedback"><span>{{$errors->first('sCnfrmPass')}}</span></div>@enderror
                                </div>
                                <button type="submit" class="btn btn-primary mb-4">Change Password</button>
                            </form>
                        </div> 
                    </div>
                    <p class="text-center mt-5 pt-4">
                            &copy; 2020 Student Lunch
                    </p>
                </div>
            </div>
        </div>
    </section>
    <script src="../assets/scripts/jquery.min.js"></script>
    <script src="../js/form-validation.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
</body>
</html>