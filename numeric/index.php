<!DOCTYPE html>
<html>
<head>
    <script type="text/javascript" src="js/jquery-1.11.3.min.js"></script>
    <script type="text/javascript" src="js/jquery.numeric.js"></script>
</head>
<body>
<form>
    Numbers only:
    <input class="numeric" type="text" />
    <br/><br/>
    Integers only:
    <input class="integer" type="text" />
    <br/><br/>
    No negative values:
    <input class="positive" type="text" />
    <br/><br/>
    No negative values (integer only):
    <input class="positive-integer" type="text" />
    <br/><br/>
    Numbers with up to 2 decimal places:
    <input class="decimal-2-places" type="text" />
    <br/><br/>
    <a href="#" id="remove">Remove numeric</a>
</form>
<script type="text/javascript">
    $(".numeric").numeric();
    $(".integer").numeric(false, function() { alert("Integers only"); this.value = ""; this.focus(); });
    $(".positive").numeric({ negative: false }, function() { alert("No negative values"); this.value = ""; this.focus(); });
    $(".positive-integer").numeric({ decimal: false, negative: false }, function() { alert("Positive integers only"); this.value = ""; this.focus(); });
    $(".decimal-2-places").numeric({ decimalPlaces: 2 });
    $("#remove").click(
        function(e)
        {
            e.preventDefault();
            $(".numeric,.integer,.positive,.positive-integer,.decimal-2-places").removeNumeric();
        }
    );
</script>
</body>
</html>