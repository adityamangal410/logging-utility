<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>


    <meta http-equiv="content-type" content="text/html; charset=utf-8">
<title>Client-side Filtering of Local Data</title>

<style type="text/css">
/*margin and padding on body element
  can introduce errors in determining
  element position and are not recommended;
  we turn them off as a foundation for YUI
  CSS treatments. */
body {
    margin:0;
	padding:0;
}
</style>

<link rel="stylesheet" type="text/css" href="http://yui.yahooapis.com/2.9.0/build/fonts/fonts-min.css" />
<link rel="stylesheet" type="text/css" href="http://yui.yahooapis.com/2.9.0/build/paginator/assets/skins/sam/paginator.css" />
<link rel="stylesheet" type="text/css" href="http://yui.yahooapis.com/2.9.0/build/datatable/assets/skins/sam/datatable.css" />
<script type="text/javascript" src="http://yui.yahooapis.com/2.9.0/build/yahoo-dom-event/yahoo-dom-event.js"></script>
<script type="text/javascript" src="http://yui.yahooapis.com/2.9.0/build/element/element-min.js"></script>
<script type="text/javascript" src="http://yui.yahooapis.com/2.9.0/build/paginator/paginator-min.js"></script>
<script type="text/javascript" src="http://yui.yahooapis.com/2.9.0/build/datasource/datasource-min.js"></script>
<script type="text/javascript" src="http://yui.yahooapis.com/2.9.0/build/event-delegate/event-delegate-min.js"></script>
<script type="text/javascript" src="http://yui.yahooapis.com/2.9.0/build/datatable/datatable-min.js"></script>

<!--there is no custom header content for this example-->

</head>

<body class="yui-skin-sam">


<h1>Client-side Filtering of Local Data</h1>

<div class="exampleIntro">
	<p>Find data quickly and easily with a client-side filter.</p>
			
</div>

<!--BEGIN SOURCE CODE FOR EXAMPLE =============================== -->

<div class="markup">
    <label for="filter">Filter by state:</label> <input type="text" id="filter" value="">

    <div id="tbl"></div>
</div>

<script type="text/javascript" src="data.js"></script>
<script type="text/javascript">
YAHOO.util.Event.addListener(window, "load", function() {
    var Ex = YAHOO.namespace('example');

    Ex.dataSource = new YAHOO.util.DataSource(YAHOO.example.Data.areacodes,{
        responseType : YAHOO.util.DataSource.TYPE_JSARRAY,
        responseSchema : {
            fields : ['areacode','state']
        },
        doBeforeCallback : function (req,raw,res,cb) {
            // This is the filter function
            var data     = res.results || [],
                filtered = [],
                i,l;

            if (req) {
                req = req.toLowerCase();
                for (i = 0, l = data.length; i < l; ++i) {
                    if (!data[i].state.toLowerCase().indexOf(req)) {
                        filtered.push(data[i]);
                    }
                }
                res.results = filtered;
            }

            return res;
        }
    });

    Ex.cols = [
        { key: 'areacode', sortable: true },
        { key: 'state', sortable: true }
    ];

    Ex.paginator = new YAHOO.widget.Paginator({
        rowsPerPage   : 10,
        pageLinks     : 5
    });

    Ex.conf = {
        paginator : Ex.paginator,
        sortedBy: {key:'areacode', dir:YAHOO.widget.DataTable.CLASS_ASC}
    };

    Ex.dataTable = new YAHOO.widget.DataTable('tbl',Ex.cols,Ex.dataSource,Ex.conf);

    Ex.filterTimeout = null;
    Ex.updateFilter  = function () {
        // Reset timeout
        Ex.filterTimeout = null;
        
        // Reset sort
        var state = Ex.dataTable.getState();
            state.sortedBy = {key:'areacode', dir:YAHOO.widget.DataTable.CLASS_ASC};

        // Get filtered data
        Ex.dataSource.sendRequest(YAHOO.util.Dom.get('filter').value,{
            success : Ex.dataTable.onDataReturnInitializeTable,
            failure : Ex.dataTable.onDataReturnInitializeTable,
            scope   : Ex.dataTable,
            argument: state
        });
    };

    YAHOO.util.Event.on('filter','keyup',function (e) {
        clearTimeout(Ex.filterTimeout);
        setTimeout(Ex.updateFilter,600);
    });
});
</script>