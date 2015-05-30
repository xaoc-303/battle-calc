<table border="1" cellspacing="0">
    <tr>
        <th width="100">Name</th>
        <th width="100">Count</th>
        <th width="100">AttackT</th>
        <th width="100">AttackA</th>
        <th width="100">AttackM</th>
        <th width="100">Armor</th>
        <th width="100">Shield</th>
        <th width="100">HP</th>
        <th width="100">Cool</th>
    </tr>
    <?php $count = count($Army);?>
    @for($i = 0; $i < $count; $i++)
        <tr style="color: <?=array_search($Army[$i]['All']['Color'], ['black' => 0, 'red' => 1, 'blue' => 2])?>;">
            <td>{{Lang::get('battle-calc::units.'.$Army[$i]['Base']['ID'])}}</td>
            <td>{{$Army[$i]['All']['ManCount']}}</td>
            <td>{{$Army[$i]['All']['AttackTer']}}</td>
            <td>{{$Army[$i]['All']['AttackAir']}}</td>
            <td>{{$Army[$i]['All']['AttackMagicAll']}}</td>
            <td>{{$Army[$i]['All']['Armor']}}</td>
            <td>{{$Army[$i]['All']['Shield']}}</td>
            <td>{{$Army[$i]['All']['HP']}}</td>
            <td>{{($Army[$i]['All']['AttackCoolDouble'] + $Army[$i]['All']['AttackCoolInt'])}}</td>
        </tr>
    @endfor
</table>
<br />