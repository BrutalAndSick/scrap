<!DOCTYPE html><html lang="en">
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="jquery.auto-complete.css">
    <script src="../dummy/js/jquery-1.11.3.min.js"></script>
    <script src="jquery.auto-complete.js"></script>
</head>
<body>
<input id="txtNumerodeParte" type="text">&nbsp;&nbsp;Seleccionado&nbsp;&nbsp;<label id="lblNumerodeParte"></label> <br />
<script>
    var objAutosuggest;
    $(function(){
        $('#txtNumerodeParte').autoComplete({
            minChars : 3,
            source : function(term,suggest){
                try { objAutosuggest.abort();} catch(e){}
                $.getJSON('getdata.php', { strNumerodeParte : term.toUpperCase(), intProc : 0 }, function(data){
                    suggest(data);
                });
            },
            renderItem: function (item, search){
                search = search.replace(/[-\/\\^$*+?.()|[\]{}]/g, '\\$&');
                var re = new RegExp("(" + search.split(' ').join('|') + ")", "gi");
                return '<div class="autocomplete-suggestion" data-numerodeparte="'+item+'" data-val="'+item+'"><img src="img/'+item+'.jpg" style="width:100px;"> '+item.replace(re, "<b>$1</b>")+'</div>';
            },
            onSelect: function(e, term, item){
                getNumerodeParte();
            }
        });

        $('#txtNumerodeParte').blur(function() {
            getNumerodeParte();
        });

        $('#txtNumerodeParte').focus(function(){
            $('#lblNumerodeParte').html('');
        });
    });

    function getNumerodeParte(){
        if($('#lblNumerodeParte').html()==''){
            $('#lblNumerodeParte').html($('#txtNumerodeParte').val());
            if($('#lblNumerodeParte').html()!=''){
                $.getJSON('getdata.php', { strNumerodeParte : $('#lblNumerodeParte').html(), intProc : 1 }, function(data){
                    console.log(data);
                });
            };
        }
    }
</script>
</body>
</html>
