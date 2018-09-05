<?php
$this->load->view('structure/header');
$this->load->view('structure/js');
?>
<script src="<?php echo assets_path ?>ckeditor/ckeditor.js"></script>
<script src="<?php echo assets_path ?>ckeditor/samples/js/sample.js"></script>
<link rel="stylesheet" href="<?php echo assets_path ?>ckeditor/samples/css/samples.css">
<link rel="stylesheet" href="<?php echo assets_path ?>ckeditor/samples/toolbarconfigurator/lib/codemirror/neo.css">

    <?php
$this->load->view('structure/nav');
$this->load->view('structure/body');
?>
<div class="row">
    <div class="col-sm-12">
        <div class="box">
        <br/>
        <div class="breadcrumbs">
                <ul>
                    <li>
                        <a href="<?php echo base_url(); ?>index.php">Home</a>
                         <i class="fa fa-angle-right" aria-hidden="true"></i>
                    </li>
                    <li>
                        <a href="">Home-page Settings</a>
                    </li>
                </ul>
        </div><br/>
        <?php
                if(strlen($this->session->userdata('home_page_update'))>0 ){
                    
                    ?><br/>
                    <div id="successMsg" class="alert alert-success alert-dismissable">
                        <button type="button" class="close" data-dismiss="alert">×</button>
                        <strong>Success!</strong>
                         <?php echo $this->session->userdata('home_page_update') ?>    
                        </div>
                    <script>
                        $("#successMsg").fadeIn();
                        $("#successMsg").delay(7000).fadeOut();
                   </script>
                    
                   <?php
                    $this->session->unset_userdata('home_page_update');
                }
                ?>
        <?php 
           $q= "select * from home_page where iid= '".$this->session->userdata("staff_Org_id")."' AND position=1 ";
           $q =$this->db->query($q);
           $pos =1;
           $position="Box Below Logo";
        ?>
        <form action="<?php echo base_url() ?>index.php/settings/save_homepage" method="post">
            <label><?php  echo $position ?></label>            
            <input type="hidden" name="position" value="<?php echo $pos ?>" />
	     <?php
               if($q->num_rows()>0){
                 $q =$q->row();
                  ?>
                      <input type="hidden" name="hid" value="<?php echo $q->id ?>" />
                      <textarea cols="80" id="editor1" name="editor1" rows="10"><?php print_r($q->text); ?></textarea>
            
                 <?php
               }else{
                   ?>
                      <textarea cols="80" id="editor1" name="editor1" rows="10"></textarea>
                    <?php
               }
             ?>
                      <span style=" color: red"><?php echo $this->form->error("editor1"); ?></span>
            <script type="text/javascript">
			//<![CDATA[

                CKEDITOR.replace( 'editor1',
                        {
                                /*
                                 * Style sheet for the contents
                                 */
                                contentsCss : 'body {color:#000; background-color#:FFF;}',

                                /*
                                 * Simple HTML5 doctype
                                 */
                                docType : '<!DOCTYPE HTML>',

                                /*
                                 * Core styles.
                                 */
                                coreStyles_bold	: { element : 'b' },
                                coreStyles_italic	: { element : 'i' },
                                coreStyles_underline	: { element : 'u'},
                                coreStyles_strike	: { element : 'strike' },

                                /*
                                 * Font face
                                 */
                                // Define the way font elements will be applied to the document. The "font"
                                // element will be used.
                                font_style :
                                {
                                                element		: 'font',
                                                attributes		: { 'face' : '#(family)' }
                                },

                                /*
                                 * Font sizes.
                                 */
                                fontSize_sizes : 'xx-small/1;x-small/2;small/3;medium/4;large/5;x-large/6;xx-large/7',
                                fontSize_style :
                                        {
                                                element		: 'font',
                                                attributes	: { 'size' : '#(size)' }
                                        } ,

                                /*
                                 * Font colors.
                                 */
                                colorButton_enableMore : true,

                                colorButton_foreStyle :
                                        {
                                                element : 'font',
                                                attributes : { 'color' : '#(color)' },
                                                overrides	: [ { element : 'span', attributes : { 'class' : /^FontColor(?:1|2|3)$/ } } ]
                                        },

                                colorButton_backStyle :
                                        {
                                                element : 'font',
                                                styles	: { 'background-color' : '#(color)' }
                                        },

                                /*
                                 * Styles combo.
                                 */
                                stylesSet :
                                                [
                                                        { name : 'Computer Code', element : 'code' },
                                                        { name : 'Keyboard Phrase', element : 'kbd' },
                                                        { name : 'Sample Text', element : 'samp' },
                                                        { name : 'Variable', element : 'var' },

                                                        { name : 'Deleted Text', element : 'del' },
                                                        { name : 'Inserted Text', element : 'ins' },

                                                        { name : 'Cited Work', element : 'cite' },
                                                        { name : 'Inline Quotation', element : 'q' }
                                                ],

                                on : { 'instanceReady' : configureHtmlOutput }
                        });

                        /*
                         * Adjust the behavior of the dataProcessor to avoid styles
                         * and make it look like FCKeditor HTML output.
                         */
                        function configureHtmlOutput( ev )
                        {
                                var editor = ev.editor,
                                        dataProcessor = editor.dataProcessor,
                                        htmlFilter = dataProcessor && dataProcessor.htmlFilter;

                                // Out self closing tags the HTML4 way, like <br>.
                                dataProcessor.writer.selfClosingEnd = '>';

                                // Make output formatting behave similar to FCKeditor
                                var dtd = CKEDITOR.dtd;
                                for ( var e in CKEDITOR.tools.extend( {}, dtd.$nonBodyContent, dtd.$block, dtd.$listItem, dtd.$tableContent ) )
                                {
                                        dataProcessor.writer.setRules( e,
                                                {
                                                        indent : true,
                                                        breakBeforeOpen : true,
                                                        breakAfterOpen : false,
                                                        breakBeforeClose : !dtd[ e ][ '#' ],
                                                        breakAfterClose : true
                                                });
                                }

                                // Output properties as attributes, not styles.
                                htmlFilter.addRules(
                                        {
                                                elements :
                                                {
                                                        $ : function( element )
                                                        {
                                                                // Output dimensions of images as width and height
                                                                if ( element.name == 'img' )
                                                                {
                                                                        var style = element.attributes.style;

                                                                        if ( style )
                                                                        {
                                                                                // Get the width from the style.
                                                                                var match = /(?:^|\s)width\s*:\s*(\d+)px/i.exec( style ),
                                                                                        width = match && match[1];

                                                                                // Get the height from the style.
                                                                                match = /(?:^|\s)height\s*:\s*(\d+)px/i.exec( style );
                                                                                var height = match && match[1];

                                                                                if ( width )
                                                                                {
                                                                                        element.attributes.style = element.attributes.style.replace( /(?:^|\s)width\s*:\s*(\d+)px;?/i , '' );
                                                                                        element.attributes.width = width;
                                                                                }

                                                                                if ( height )
                                                                                {
                                                                                        element.attributes.style = element.attributes.style.replace( /(?:^|\s)height\s*:\s*(\d+)px;?/i , '' );
                                                                                        element.attributes.height = height;
                                                                                }
                                                                        }
                                                                }

                                                                // Output alignment of paragraphs using align
                                                                if ( element.name == 'p' )
                                                                {
                                                                        style = element.attributes.style;

                                                                        if ( style )
                                                                        {
                                                                                // Get the align from the style.
                                                                                match = /(?:^|\s)text-align\s*:\s*(\w*);/i.exec( style );
                                                                                var align = match && match[1];

                                                                                if ( align )
                                                                                {
                                                                                        element.attributes.style = element.attributes.style.replace( /(?:^|\s)text-align\s*:\s*(\w*);?/i , '' );
                                                                                        element.attributes.align = align;
                                                                                }
                                                                        }
                                                                }

                                                                if ( !element.attributes.style )
                                                                        delete element.attributes.style;

                                                                return element;
                                                        }
                                                },

                                                attributes :
                                                        {
                                                                style : function( value, element )
                                                                {
                                                                        // Return #RGB for background and border colors
                                                                        return convertRGBToHex( value );
                                                                }
                                                        }
                                        } );
                        }


                        /**
                        * Convert a CSS rgb(R, G, B) color back to #RRGGBB format.
                        * @param Css style string (can include more than one color
                        * @return Converted css style.
                        */
                        function convertRGBToHex( cssStyle )
                        {
                                return cssStyle.replace( /(?:rgb\(\s*(\d+)\s*,\s*(\d+)\s*,\s*(\d+)\s*\))/gi, function( match, red, green, blue )
                                        {
                                                red = parseInt( red, 10 ).toString( 16 );
                                                green = parseInt( green, 10 ).toString( 16 );
                                                blue = parseInt( blue, 10 ).toString( 16 );
                                                var color = [red, green, blue] ;

                                                // Add padding zeros if the hex value is less than 0x10.
                                                for ( var i = 0 ; i < color.length ; i++ )
                                                        color[i] = String( '0' + color[i] ).slice( -2 ) ;

                                                return '#' + color.join( '' ) ;
                                         });
                        }
			//]]>
			</script>
		
		<p>
			<input type="submit" value="Submit" />
		</p>
	</form>
        </div>
    </div>
</div>

<script>
	initSample();
        CKEDITOR.replace( 'editor' );
</script>

<?php

$this->load->view('structure/footer');

?>
    