<table>
    <tr>
        <th width="100">name</th>
        <th width="100">count</th>
        <th width="100">init</th>
        <th width="100">type</th>
        <th width="100">shield</th>
        <th width="100">armor</th>
        <th width="100">hp</th>
        <th width="100">mround</th>
        <th width="100">cool</th>
        <th width="100">attack_ter</th>
        <th width="100">attack_air</th>
        <th width="100">attack_magic</th>
        <th width="100">magic1</th>
        <th width="100">magic2</th>
        <th width="100">magic3</th>
    </tr>
    @foreach($army as $unit)
        <?php $race = $unit['race_id'] ?>
        <tr>
            <td>{{ Lang::get('battle-calc::units.'.$unit['id']) }}</td>
            <td><input name="army-{{$id_army}}[{{$unit['id']}}]" type="text" value="0" /></td>
            <td>{{$unit['init']}}</td>
            <td>{{$unit['type']}}</td>
            <td>{{$unit['shield']}}</td>
            <td>{{$unit['armor']}}</td>
            <td>{{$unit['hp']}}</td>
            <td>{{$unit['mround']}}</td>
            <td>{{$unit['cool']}}</td>
            <td>{{$unit['attack_ter']}}</td>
            <td>{{$unit['attack_air']}}</td>
            <td>{{$unit['attack_magic']}}</td>
            <td>{{$unit['magic1']}}</td>
            <td>{{$unit['magic2']}}</td>
            <td>{{$unit['magic3']}}</td>
        </tr>
    @endforeach
</table>
<input type="hidden" name="race-{{$id_army}}" value="{{$race}}" />