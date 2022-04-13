
  <!-- ======= Footer ======= -->
  <footer id="footer" class="footer">
    <div class="copyright">
      &copy; Copyright  {{ date('Y') }}  <strong><span>Delivery</span></strong>. All Rights Reserved
    </div>
    <div class="credits">
      Designed by <a href=""></a>
    </div>
  </footer><!-- End Footer -->

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <!-- Vendor JS Files -->
  <script src="{{ asset('assets/vendor/apexcharts/apexcharts.min.js') }}"></script>
  <script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset('assets/vendor/chart.js/chart.min.js') }}"></script>
  <script src="{{ asset('assets/vendor/echarts/echarts.min.js') }}"></script>
  <script src="{{ asset('assets/vendor/quill/quill.min.js') }}"></script>
  <script src="{{ asset('assets/vendor/simple-datatables/simple-datatables.js') }}"></script>
  <script src="{{ asset('assets/vendor/tinymce/tinymce.min.js') }}"></script>
  <script src="{{ asset('assets/vendor/php-email-form/validate.js') }}"></script>

  <!-- Template Main JS File -->
  <script src="{{ asset('assets/js/main.js') }}"></script>

  

  <script src="https://cdnjs.cloudflare.com/ajax/libs/prelodr/2.1.1/prelodr.min.js"></script>
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script> -->
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>


<script type="text/javascript">
  // Active Inactive Inquiry Status
    $(document).on('click', '.merchentStatus', function() {
        var enquiry_id = $(this).val();
        var token = $('meta[name="csrf-token"]').attr('content');
        console.log(enquiry_id);
        swal({
            title: "Make Sure!!!",
            text: "Do you want to change status ?",
            icon: "success",
            CancelButtonColor: '#d33',
            closeOnClickOutside: false,
          buttons: {
            cancel: "No",
            defeat: "Yes",
          },
        }).then((value) => {
              switch (value) {
             
                case "defeat":
                    $.ajax({
                        type: "POST",
                        url: '/change/merchent/status',
                        data: {enquiry_id:enquiry_id,
                            _token : token},
                        success: function (data) {
                            swal("Merchent Status Updated Successfully", {
                                icon: "success",
                                confirmButtonColor: '#d33',
                                buttons: 'Okay',
                                dangerMode: true,
                            }).then(function(isConfirm) {
                                if (isConfirm) {
                                    location.reload();
                                }
                            });
                            }         
                    });
                break;
             
                default:
                  swal("Status is not change!");
              }
            });
    });
</script>

</body>

</html>