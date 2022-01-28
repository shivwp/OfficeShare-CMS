@extends('layouts.admin')
@section('title', 'ghg')
@section('content')
<!-- /Row -->
<div class="card">
    <div class="card-header">
        <h4>{{ '' }}</h4>
    </div>
</div>
<div class="card">
<div class="card-body" id="add_space">
<section style="background-color: #fff;">
<div class="mail_image"><img alt="" src="http://officeshare-cms.ewtlive.in/images/mail_img_1629200730.png" /></div>

<div class="mail-heading" style="margin-top: 30px; margin-left: 30px; font-family:Poppins;">
<h1 style="font-size: 25px;font-weight: 400;font-family:Poppins;">Welcome to <span class="mail_head" style="color: #fc6565; font-size:25px;font-weight: 400; font-family:Poppins;">OfficeShare</span>!</h1>
</div>

<div class="mail_text" style="margin-top: 15px; margin-left: 30px">
<h3 style="color: #fc6565; font-size: 20px;line-height: 40px;margin: 20px 0 10px;font-weight: 500; font-family:Poppins;">Lorum Ipsum:</h3>

<p style="margin-top: 10px;font-size: 14px;line-height: 33px;font-weight: 400;font-family:Poppins;">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
</div>

<div class="mail_text" style="margin-top: 15px; margin-left: 30px">
<h3 style="color: #fc6565; font-size: 20px;line-height: 40px;margin: 20px 0 10px;font-weight: 500; font-family:Poppins;">Before you get started:</h3>

<p style="margin-top: 10px;font-size: 14px;line-height: 33px;font-weight: 400;font-family:Poppins;">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
</div>
<style type="text/css">a:hover {text-decoration: none;} a{
text-decoration: none;
    cursor: pointer;}
.mail_image img{
height:100%;
width:100%;
}
</style>
<div class="vertical-center" style="text-align: center;"><a href="{token}" style="margin-top:3vw; background-color: #fe5f5f;color: #fff;padding: 5px 45px ; border: 0px;font-size: 16px;font-family: poppins; border-radius: 58px;font-weight: 600;">Verify your mail</a></div>
</section>
</div>
</div>

<style type="text/css">
    body{
        font-family: poppins;
    }
</style>
@push('ajax-script')
<!-- Edit CAT -->
<script type="text/javascript">
    $(document).on('change', '#chk', function(event) {
        let v = $(this).val();

        if (v) {
            $("#chkSel option").attr('selected', 'selected');
        } else {
            $("#chkSel option").removeAttr('selected')
        }
    });
</script>
<script type="text/javascript">
    $(".delattribute").click(function(event) {
        var id = $(this).parents('tr').attr('id');
        const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                confirmButton: 'btn btn-success',
                cancelButton: 'btn btn-danger'
            },
            buttonsStyling: false
        })
        swalWithBootstrapButtons.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'No, cancel!',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {

                $.ajax({
                    url: "{{ url('admin/attribute') }}/" + id,
                    type: 'DELETE',
                    data: {
                        id: id,
                        _token: '{{csrf_token() }}'
                    },
                    success: function(data) {
                        swalWithBootstrapButtons.fire(
                            'Deleted!',
                            'Your file has been deleted.',
                            'success'
                        )

                        $("#" + id).remove()
                    }
                })
            } else if (
                /* Read more about handling dismissals below */
                result.dismiss === Swal.DismissReason.cancel
            ) {
                swalWithBootstrapButtons.fire(
                    'Cancelled',
                    'Your imaginary file is safe :)',
                    'error'
                )
            }
        })
    });
</script>


<!-- for data search -->
<script>
    $(document).ready(function() {
        $("#InputCat").on("keyup", function() {
            var value = $(this).val().toLowerCase();
            $(".c tr").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });
    });
</script>
@endpush
@endsection