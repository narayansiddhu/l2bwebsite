<?php
    // New Theme implementation headers->css and js plugines
    $this->load->view('structure/header');
    $this->load->view('structure/js');
    //--> New Theme implementation headers->css and js plugines
?>

<body>
<div id="wrapper">

    <?php
    // Navigation Links in right side panel
        $this->load->view('structure/body');
    //--> Navigation Links in right side panel


    ?>
    <div id="page-wrapper" class="gray-bg dashbard-1">
        <div class="row border-bottom">
            <?php $this->load->view('structure/nav'); ?>
        </div>


        <!--- Contenet -->

        <!--//-- Conentent End -->
        <?php   $this->load->view('structure/footer'); ?>
    </div>
</div>

</body>

</html>
