<?php if(!empty($data['feedback'])):?>
    <div class="feedback">
        <p class="message <?= $data['feedback']['type']?>"><?= $data['feedback']['msg']?></p>
    </div>
<?php endif;?>
<div class="callList">
    <?php if(!empty($data['Calls'])):?>
        <?php foreach($data['Calls'] as $date => $callsByDate):?>
            <div class="mb50">
                <h2 class="date">Atendimentos em <?=$date?></h2>
                <?php foreach($callsByDate as $uf => $callsByUf):?>
                    <table class="mb10">
                        <thead class="tableHeader">
                            <th colspan="5"><?=$uf?></th>
                        </thead>
                        <tr class="tableSubHeader">
                            <td class="dateCell">Horário</td>
                            <td class="typeCell">Tipo de chamada</td>
                            <td class="reasonCell">Motivo</td>
                            <td class="detailCell">Detalhes</td>
                        </tr>
                        <?php foreach($callsByUf as $Call):?>
                            <tr>
                                <td class="dateValue"><?=$Call->getCreated('onlyHour');?></td>
                                <td class="typeValue"><?=$Call->getType(true);?></td>
                                <td class="codesValue"><?=$Call->getReason(true) ;?></td>
                                <td class="detailValue"><?=$Call->getDetails() ;?></td>
                            </tr>
                        <?php endforeach;?>
                    </table>
                <?php endforeach;?>
            </div>
        <?php endforeach;?>
    <?php endif;?>
</div>