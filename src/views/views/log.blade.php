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
    <?php $count = count($units);?>
    @for($i = 0; $i < $count; $i++)
        <tr style="color: {{array_search($units[$i]['All']['Color'], ['black' => 0, 'red' => 1, 'blue' => 2])}};">
            <td>{{Lang::get('battle-calc::units.'.$units[$i]['Base']['ID'])}}</td>
            <td>{{$units[$i]['All']['ManCount']}}</td>
            <td>{{$units[$i]['All']['AttackTer']}}</td>
            <td>{{$units[$i]['All']['AttackAir']}}</td>
            <td>{{$units[$i]['All']['AttackMagicAll']}}</td>
            <td>{{$units[$i]['All']['Armor']}}</td>
            <td>{{$units[$i]['All']['Shield']}}</td>
            <td>{{$units[$i]['All']['HP']}}</td>
            <td>{{($units[$i]['All']['AttackCoolDouble'] + $units[$i]['All']['AttackCoolInt'])}}</td>
        </tr>
    @endfor
</table>
<br />