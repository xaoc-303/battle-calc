<?=Form::open(['url' => 'calc/calculation', 'method' => 'get',  'id' => 'form-army'])?>
    <div>
        <?php
        $races = [
            1 => Lang::get('battle-calc::races.1'),
            2 => Lang::get('battle-calc::races.2'),
            3 => Lang::get('battle-calc::races.3')
        ]
        ?>
        <?=Form::select('', $races, 1, ['class' => "select-army",  "data-id-army" => "1"])?>
        <div id="army1"></div>
    </div>
    <hr />
    <div>
        <?=Form::select('', $races, 2, ['class' => "select-army",  "data-id-army" => "2"])?>
        <div id="army2"></div>
    </div>
<?=Form::submit('Расчет', ["id" => "but-calculation"])?>
<?=Form::close()?>
<div id="log"></div>
<hr />

<script type="text/javascript">
    function calculation(){
        $('#form-army').ajaxSubmit({
            success: function(data){
                $("#log").html(data);
            }
            /*,
             beforeSubmit: function(arr, $form, options) {
             $("#tab-content").html('<img id="loader" src="/images/ajax-loader.gif" alt="загрузка" />');
             }*/
        });
        return false;
    };

    function army( id_army, race ){
        console.log(id_army + ' ' + race);
        $.get('calc/GetArmyBase/'+id_army+'/'+race,
            function(response){
                $("#army"+id_army).html(response);
            });
        return false;
    };

    $(function() {
        $('.select-army').on('change', function(){
            army( $(this).attr("data-id-army"), $(this).children('option:selected').val() );
        });
        $('#but-calculation').on('submit', function( event ) {

        });
        army(1,$('.select-army[data-id-army = 1]').children('option:selected').val() );
        army(2,$('.select-army[data-id-army = 2]').children('option:selected').val() );
    });
</script>