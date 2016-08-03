<script language="JavaScript" type="text/javascript" src="<?php echo plugins_url('wpdm-custom-access-level/js/jquery.dataTables.min.js'); ?>"></script> 
<link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/themes/flick/jquery-ui.min.css" type="text/css" media="all" />
<link rel="stylesheet" href="<?php echo plugins_url('wpdm-custom-access-level/css/demo_table_jui.css'); ?>" type="text/css" media="all" />
<link rel="stylesheet" href="<?php echo plugins_url('wpdm-custom-access-level/css/jquery.dataTables.css'); ?>" type="text/css" media="all" />
<div class="w3eden">
<table id="wpdmmydls" style="width: 100%;" class="dtable table table-bordered">
<thead><tr><th>Title</th><th class="file-size">File Size</th></tr></thead>
<tbody>
<?php if($files) { while($files->have_posts()):  $files->the_post(); ?>
<tr><td><a href='<?php the_permalink(); ?>'><?php the_title(); ?></a></td><td class="file-size"><?php echo wpdm_package_size(get_the_ID()); ?></td></tr>
<?php endwhile; } else { echo "<tr><td colspan='2'>Sorry! No files shared for you.</td><td></td></tr>"; } ?>
</tbody>
</table></div>
<script type="text/javascript" charset="utf-8">
    /* Default class modification */
    jQuery.extend( jQuery.fn.dataTableExt.oStdClasses, {
        "sSortAsc": "header headerSortDown",
        "sSortDesc": "header headerSortUp",
        "sSortable": "header"
    } );

    jQuery.fn.dataTableExt.aTypes.unshift(
        function ( sData )
        {
            var sValidChars = "0123456789";
            var Char;

            /* Check the numeric part */
            for ( i=0 ; i<(sData.length - 3) ; i++ )
            {
                Char = sData.charAt(i);
                if (sValidChars.indexOf(Char) == -1)
                {
                    return null;
                }
            }

            /* Check for size unit KB, MB or GB */
            if ( sData.substring(sData.length - 2, sData.length) == "KB"
                || sData.substring(sData.length - 2, sData.length) == "MB"
                || sData.substring(sData.length - 2, sData.length) == "GB" )
            {
                return 'file-size';
            }
            return null;
        }
    );

    jQuery.extend( jQuery.fn.dataTableExt.oSort, {
        "file-size-pre": function ( a ) {
            var x = a.substring(0,a.length - 2);

            var x_unit = (a.substring(a.length - 2, a.length) == "MB" ?
                1000 : (a.substring(a.length - 2, a.length) == "GB" ? 1000000 : 1));

            return parseInt( x * x_unit, 10 );
        },

        "file-size-asc": function ( a, b ) {
            return ((a < b) ? -1 : ((a > b) ? 1 : 0));
        },

        "file-size-desc": function ( a, b ) {
            return ((a < b) ? 1 : ((a > b) ? -1 : 0));
        }
    } );

    /* API method to get paging information */
    jQuery.fn.dataTableExt.oApi.fnPagingInfo = function ( oSettings )
    {
        return {
            "iStart":         oSettings._iDisplayStart,
            "iEnd":           oSettings.fnDisplayEnd(),
            "iLength":        oSettings._iDisplayLength,
            "iTotal":         oSettings.fnRecordsTotal(),
            "iFilteredTotal": oSettings.fnRecordsDisplay(),
            "iPage":          Math.ceil( oSettings._iDisplayStart / oSettings._iDisplayLength ),
            "iTotalPages":    Math.ceil( oSettings.fnRecordsDisplay() / oSettings._iDisplayLength )
        };
    }

    /* Bootstrap style pagination control */
    jQuery.extend( jQuery.fn.dataTableExt.oPagination, {
        "bootstrap": {
            "fnInit": function( oSettings, nPaging, fnDraw ) {
                var oLang = oSettings.oLanguage.oPaginate;
                var fnClickHandler = function ( e ) {
                    e.preventDefault();
                    if ( oSettings.oApi._fnPageChange(oSettings, e.data.action) ) {
                        fnDraw( oSettings );
                    }
                };

                jQuery(nPaging).append(
                    '<ul class="pagination">'+
                        '<li class="prev disabled"><a href="#">&larr; '+oLang.sPrevious+'</a></li>'+
                        '<li class="next disabled"><a href="#">'+oLang.sNext+' &rarr; </a></li>'+
                        '</ul>'
                );
                var els = jQuery('a', nPaging);
                jQuery(els[0]).bind( 'click.DT', { action: "previous" }, fnClickHandler );
                jQuery(els[1]).bind( 'click.DT', { action: "next" }, fnClickHandler );
            },

            "fnUpdate": function ( oSettings, fnDraw ) {
                var iListLength = 5;
                var oPaging = oSettings.oInstance.fnPagingInfo();
                var an = oSettings.aanFeatures.p;
                var i, j, sClass, iStart, iEnd, iHalf=Math.floor(iListLength/2);

                if ( oPaging.iTotalPages < iListLength) {
                    iStart = 1;
                    iEnd = oPaging.iTotalPages;
                }
                else if ( oPaging.iPage <= iHalf ) {
                    iStart = 1;
                    iEnd = iListLength;
                } else if ( oPaging.iPage >= (oPaging.iTotalPages-iHalf) ) {
                    iStart = oPaging.iTotalPages - iListLength + 1;
                    iEnd = oPaging.iTotalPages;
                } else {
                    iStart = oPaging.iPage - iHalf + 1;
                    iEnd = iStart + iListLength - 1;
                }

                for ( i=0, iLen=an.length ; i<iLen ; i++ ) {
                    // Remove the middle elements
                    jQuery('li:gt(0)', an[i]).filter(':not(:last)').remove();

                    // Add the new list items and their event handlers
                    for ( j=iStart ; j<=iEnd ; j++ ) {
                        sClass = (j==oPaging.iPage+1) ? 'class="active"' : '';
                        jQuery('<li '+sClass+'><a href="#">'+j+'</a></li>')
                            .insertBefore( jQuery('li:last', an[i])[0] )
                            .bind('click', function (e) {
                                e.preventDefault();
                                oSettings._iDisplayStart = (parseInt(jQuery('a', this).text(),10)-1) * oPaging.iLength;
                                fnDraw( oSettings );
                            } );
                    }

                    // Add / remove disabled classes from the static elements
                    if ( oPaging.iPage === 0 ) {
                        jQuery('li:first', an[i]).addClass('disabled');
                    } else {
                        jQuery('li:first', an[i]).removeClass('disabled');
                    }

                    if ( oPaging.iPage === oPaging.iTotalPages-1 || oPaging.iTotalPages === 0 ) {
                        jQuery('li:last', an[i]).addClass('disabled');
                    } else {
                        jQuery('li:last', an[i]).removeClass('disabled');
                    }
                }
            }
        }
    } );

    /* Table initialisation */
    jQuery(document).ready(function($) {
        jQuery('#wpdmmydls').dataTable( {
            "sDom": "<'row'<'col-md-6'l><'col-md-6'f>r>t<'row'<'col-md-6'i><'col-md-6'p>>",
            "sPaginationType": "bootstrap",
            "oLanguage": {
                "sLengthMenu": "_MENU_ records per page"
            }
        } );
    } );
</script>