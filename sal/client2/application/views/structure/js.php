<!-- Mainly scripts -->
<script src="<?= assets_path_admin ?>js/jquery-3.1.1.min.js"></script>
<script src="<?= assets_path_admin ?>js/bootstrap.min.js"></script>
<script src="<?= assets_path_admin ?>js/plugins/metisMenu/jquery.metisMenu.js"></script>
<script src="<?= assets_path_admin ?>js/plugins/slimscroll/jquery.slimscroll.min.js"></script>

<!-- Flot -->
<script src="<?= assets_path_admin ?>js/plugins/flot/jquery.flot.js"></script>
<script src="<?= assets_path_admin ?>js/plugins/flot/jquery.flot.tooltip.min.js"></script>
<script src="<?= assets_path_admin ?>js/plugins/flot/jquery.flot.spline.js"></script>
<script src="<?= assets_path_admin ?>js/plugins/flot/jquery.flot.resize.js"></script>
<script src="<?= assets_path_admin ?>js/plugins/flot/jquery.flot.pie.js"></script>
<script src="<?= assets_path_admin ?>js/plugins/flot/jquery.flot.symbol.js"></script>
<script src="<?= assets_path_admin ?>js/plugins/flot/jquery.flot.time.js"></script>


<!--- Ajax Functions  ---->
<script src="<?php echo assets_path ?>js/ajaxfunction.js"></script>

<!-- Peity -->
<script src="<?= assets_path_admin ?>js/plugins/peity/jquery.peity.min.js"></script>
<script src="<?= assets_path_admin ?>js/demo/peity-demo.js"></script>

<!-- Custom and plugin javascript -->
<script src="<?= assets_path_admin ?>js/inspinia.js"></script>
<script src="<?= assets_path_admin ?>js/plugins/pace/pace.min.js"></script>

<!-- jQuery UI -->
<script src="<?= assets_path_admin ?>js/plugins/jquery-ui/jquery-ui.min.js"></script>

<!-- GITTER -->
<script src="<?= assets_path_admin ?>js/plugins/gritter/jquery.gritter.min.js"></script>

<!-- Jvectormap -->
<script src="<?= assets_path_admin ?>js/plugins/jvectormap/jquery-jvectormap-2.0.2.min.js"></script>
<script src="<?= assets_path_admin ?>js/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>

<!-- Sweet alert -->
<script src="<?= assets_path_admin ?>js/plugins/sweetalert/sweetalert.min.js"></script>
<!-- EayPIE -->
<script src="<?= assets_path_admin ?>js/plugins/easypiechart/jquery.easypiechart.js"></script>

<!-- wow animations -->
<script src="<?= assets_path_admin ?>js/plugins/wow/owl.carousel.min.js"></script>
<script src="<?= assets_path_admin ?>js/plugins/wow/wow.js"></script>

<!-- Sparkline -->
<script src="<?= assets_path_admin ?>js/plugins/sparkline/jquery.sparkline.min.js"></script>

<!-- Sparkline demo data  -->
<script src="<?= assets_path_admin ?>js/demo/sparkline-demo.js"></script>

<!-- ChartJS-->
<script src="<?= assets_path_admin ?>js/plugins/chartJs/Chart.min.js"></script>

<!-- Toastr -->
<script src="<?= assets_path_admin ?>js/plugins/toastr/toastr.min.js"></script>

<!-- FooTable -->
<script src="<?= assets_path_admin ?>js/plugins/footable/footable.all.min.js"></script>

<!-- From Elements-->

<!-- Form elements JS-->

<!-- Jquery Validate -->
<script src="<?= assets_path_admin ?>js/plugins/validate/jquery.validate.min.js"></script>

<!-- Chosen -->
<script src="<?= assets_path_admin ?>js/plugins/chosen/chosen.jquery.js"></script>

<!-- JSKnob -->
<script src="<?= assets_path_admin ?>js/plugins/jsKnob/jquery.knob.js"></script>

<!-- Input Mask-->
<script src="<?= assets_path_admin ?>js/plugins/jasny/jasny-bootstrap.min.js"></script>

<!-- Data picker -->
<script src="<?= assets_path_admin ?>js/plugins/datapicker/bootstrap-datepicker.js"></script>

<!-- NouSlider -->
<script src="<?= assets_path_admin ?>js/plugins/nouslider/jquery.nouislider.min.js"></script>

<!-- Switchery -->
<script src="<?= assets_path_admin ?>js/plugins/switchery/switchery.js"></script>

<!-- IonRangeSlider -->
<script src="<?= assets_path_admin ?>js/plugins/ionRangeSlider/ion.rangeSlider.min.js"></script>

<!-- iCheck -->
<script src="<?= assets_path_admin ?>js/plugins/iCheck/icheck.min.js"></script>

<!-- MENU -->
<script src="<?= assets_path_admin ?>js/plugins/metisMenu/jquery.metisMenu.js"></script>

<!-- Color picker -->
<script src="<?= assets_path_admin ?>js/plugins/colorpicker/bootstrap-colorpicker.min.js"></script>

<!-- Clock picker -->
<script src="<?= assets_path_admin ?>js/plugins/clockpicker/clockpicker.js"></script>

<!-- Image cropper -->
<script src="<?= assets_path_admin ?>js/plugins/cropper/cropper.min.js"></script>

<!-- Date range use moment.js same as full calendar plugin -->
<script src="<?= assets_path_admin ?>js/plugins/fullcalendar/moment.min.js"></script>

<!-- Date range picker -->
<script src="<?= assets_path_admin ?>js/plugins/daterangepicker/daterangepicker.js"></script>

<!-- Select2 -->
<script src="<?= assets_path_admin ?>js/plugins/select2/select2.full.min.js"></script>

<!-- TouchSpin -->
<script src="<?= assets_path_admin ?>js/plugins/touchspin/jquery.bootstrap-touchspin.min.js"></script>

<!-- Tags Input -->
<script src="<?= assets_path_admin ?>js/plugins/bootstrap-tagsinput/bootstrap-tagsinput.js"></script>

<!-- Dual Listbox -->
<script src="<?= assets_path_admin ?>js/plugins/dualListbox/jquery.bootstrap-duallistbox.js"></script>


<!-- // From Elements-->


<script>
    $(document).ready(function(){

        $('.tagsinput').tagsinput({
            tagClass: 'label label-primary'
        });

        var $image = $(".image-crop > img")
        $($image).cropper({
            aspectRatio: 1.618,
            preview: ".img-preview",
            done: function(data) {
                // Output the result data for cropping image.
            }
        });

        var $inputImage = $("#inputImage");
        if (window.FileReader) {
            $inputImage.change(function() {
                var fileReader = new FileReader(),
                    files = this.files,
                    file;

                if (!files.length) {
                    return;
                }

                file = files[0];

                if (/^image\/\w+$/.test(file.type)) {
                    fileReader.readAsDataURL(file);
                    fileReader.onload = function () {
                        $inputImage.val("");
                        $image.cropper("reset", true).cropper("replace", this.result);
                    };
                } else {
                    showMessage("Please choose an image file.");
                }
            });
        } else {
            $inputImage.addClass("hide");
        }

        $("#download").click(function() {
            window.open($image.cropper("getDataURL"));
        });

        $("#zoomIn").click(function() {
            $image.cropper("zoom", 0.1);
        });

        $("#zoomOut").click(function() {
            $image.cropper("zoom", -0.1);
        });

        $("#rotateLeft").click(function() {
            $image.cropper("rotate", 45);
        });

        $("#rotateRight").click(function() {
            $image.cropper("rotate", -45);
        });

        $("#setDrag").click(function() {
            $image.cropper("setDragMode", "crop");
        });

        $('#data_1 .input-group.date').datepicker({
            todayBtn: "linked",
            keyboardNavigation: false,
            forceParse: false,
            calendarWeeks: true,
            autoclose: true
        });

        $('#data_2 .input-group.date').datepicker({
            startView: 1,
            todayBtn: "linked",
            keyboardNavigation: false,
            forceParse: false,
            autoclose: true,
            format: "dd/mm/yyyy"
        });

        $('#data_3 .input-group.date').datepicker({
            startView: 2,
            todayBtn: "linked",
            keyboardNavigation: false,
            forceParse: false,
            autoclose: true
        });

        $('#data_4 .input-group.date').datepicker({
            minViewMode: 1,
            keyboardNavigation: false,
            forceParse: false,
            forceParse: false,
            autoclose: true,
            todayHighlight: true
        });

        $('#data_5 .input-daterange').datepicker({
            keyboardNavigation: false,
            forceParse: false,
            autoclose: true
        });


        $('.i-checks').iCheck({
            checkboxClass: 'icheckbox_square-green',
            radioClass: 'iradio_square-green'
        });






        $('#reportrange span').html(moment().subtract(29, 'days').format('MMMM D, YYYY') + ' - ' + moment().format('MMMM D, YYYY'));

        $('#reportrange').daterangepicker({
            format: 'MM/DD/YYYY',
            startDate: moment().subtract(29, 'days'),
            endDate: moment(),
            minDate: '01/01/2012',
            maxDate: '12/31/2015',
            dateLimit: { days: 60 },
            showDropdowns: true,
            showWeekNumbers: true,
            timePicker: false,
            timePickerIncrement: 1,
            timePicker12Hour: true,
            ranges: {
                'Today': [moment(), moment()],
                'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                'This Month': [moment().startOf('month'), moment().endOf('month')],
                'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            },
            opens: 'right',
            drops: 'down',
            buttonClasses: ['btn', 'btn-sm'],
            applyClass: 'btn-primary',
            cancelClass: 'btn-default',
            separator: ' to ',
            locale: {
                applyLabel: 'Submit',
                cancelLabel: 'Cancel',
                fromLabel: 'From',
                toLabel: 'To',
                customRangeLabel: 'Custom',
                daysOfWeek: ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr','Sa'],
                monthNames: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
                firstDay: 1
            }
        }, function(start, end, label) {
            console.log(start.toISOString(), end.toISOString(), label);
            $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
        });

        $(".select2_demo_1").select2();
        $(".select2_demo_2").select2();
        $(".select2_demo_3").select2({
            placeholder: "Select a state",
            allowClear: true
        });

    });

    $('.chosen-select').chosen({width: "100%"});

    $("#ionrange_1").ionRangeSlider({
        min: 0,
        max: 5000,
        type: 'double',
        prefix: "$",
        maxPostfix: "+",
        prettify: false,
        hasGrid: true
    });

    $("#ionrange_2").ionRangeSlider({
        min: 0,
        max: 10,
        type: 'single',
        step: 0.1,
        postfix: " carats",
        prettify: false,
        hasGrid: true
    });

    $("#ionrange_3").ionRangeSlider({
        min: -50,
        max: 50,
        from: 0,
        postfix: "°",
        prettify: false,
        hasGrid: true
    });

    $("#ionrange_4").ionRangeSlider({
        values: [
            "January", "February", "March",
            "April", "May", "June",
            "July", "August", "September",
            "October", "November", "December"
        ],
        type: 'single',
        hasGrid: true
    });

    $("#ionrange_5").ionRangeSlider({
        min: 10000,
        max: 100000,
        step: 100,
        postfix: " km",
        from: 55000,
        hideMinMax: true,
        hideFromTo: false
    });

    $(".dial").knob();

    var basic_slider = document.getElementById('basic_slider');

    noUiSlider.create(basic_slider, {
        start: 40,
        behaviour: 'tap',
        connect: 'upper',
        range: {
            'min':  20,
            'max':  80
        }
    });

    var range_slider = document.getElementById('range_slider');

    noUiSlider.create(range_slider, {
        start: [ 40, 60 ],
        behaviour: 'drag',
        connect: true,
        range: {
            'min':  20,
            'max':  80
        }
    });

    var drag_fixed = document.getElementById('drag-fixed');

    noUiSlider.create(drag_fixed, {
        start: [ 40, 60 ],
        behaviour: 'drag-fixed',
        connect: true,
        range: {
            'min':  20,
            'max':  80
        }
    });


</script>
<!-- // form elements -->



<!-- Page-Level Scripts footable -->
<script>
    $(document).ready(function() {

        $('.footable').footable();
        $('.footable2').footable();

    });

</script>
<!-- Clipboard -->
<script src="<?= assets_path_admin ?>js/plugins/clipboard/clipboard.min.js"></script>

<!-- Data-Table -->
<script src="<?= assets_path_admin ?>js/plugins/dataTables/datatables.min.js"></script>

<!-- Page-Level Scripts Export Buttons-->
<script>
    $(document).ready(function(){
        $('.dataTables-example').DataTable({
            pageLength: 25,
            responsive: true,
            dom: '<"html5buttons"B>lTfgitp',
            buttons: [
                { extend: 'copy'},
                {extend: 'csv'},
                {extend: 'excel', title: 'ExampleFile'},
                {extend: 'pdf', title: 'ExampleFile'},

                {extend: 'print',
                    customize: function (win){
                        $(win.document.body).addClass('white-bg');
                        $(win.document.body).css('font-size', '10px');

                        $(win.document.body).find('table')
                            .addClass('compact')
                            .css('font-size', 'inherit');
                    }
                }
            ]

        });

        new WOW().init();

    });

</script>
