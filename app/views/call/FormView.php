<div class="newCall">
    <div class="formHeader top-border-radius clearfix">
        <figure class="phone">
            <img src="/app/assets/svg/phoneWhite.svg?version=<?=$version?>" alt="icone telefone" width="" height="">
        </figure>
        <h2 class="formTitle">Registrar Atendimento</h2>

    </div>

    <form class="formContent bottom-border-radius" name="createCall" action="/atendimento/salvar" method="post">
        <div class="formItem type">
            <label for="formType">Tipo de atendimento:</label>
            <select  <?php if(isset($data['errors']['formType'][0])):?>class="borderValidation"<?php endif;?>  name="formType">
                <option value="chat" <?php if(isset($data['formType']) && $data['formType'] == 'chat'):?>selected<?php endif;?> >Chat</option>
                <option value="email" <?php if(isset($data['formType']) && $data['formType'] == 'email'):?>selected<?php endif;?> >Email</option>
                <option value="phone" <?php if(isset($data['formType']) && $data['formType'] == 'phone'):?>selected<?php endif;?> >Telefone</option>
            </select>
            <?php if(isset($data['errors']['formType'][0])):?>
                <p class="noticeValidation">*<?=$data['errors']['formType'][0]?></p>
            <?php endif;?>
        </div>
        <div class="formItem reason">
            <label for="formReason">Motivo do contato:</label>
            <select  <?php if(isset($data['errors']['formReason'][0])):?>class="borderValidation"<?php endif;?>  name="formReason">
                <option value="doubt" <?php if(isset($data['formReason']) && $data['formReason'] == 'doubt'):?>selected<?php endif;?> >Dúvidas</option>
                <option value="praise" <?php if(isset($data['formReason']) && $data['formReason'] == 'praise'):?>selected<?php endif;?> >Elogio</option>
                <option value="suggestion" <?php if(isset($data['formReason']) && $data['formReason'] == 'suggestion'):?>selected<?php endif;?> >Sugestão</option>
            </select>
            <?php if(isset($data['errors']['formReason'][0])):?>
                <p class="noticeValidation">*<?=$data['errors']['formReason'][0]?></p>
            <?php endif;?>
        </div>
        <div class="formItem region">
            <label for="formRegion">Estado:</label>
            <select  <?php if(isset($data['errors']['formRegion'][0])):?>class="borderValidation"<?php endif;?>  name="formRegion">
                <?php foreach($data['regions'] as $region):?>
                    <option value="<?=$region->getId()?>" <?php if(isset($data['formRegion']) && $data['formRegion'] == $region->getId()):?>selected<?php endif;?> ><?=$region->getName()?></option>
                <?php endforeach;?>
            </select>
            <?php if(isset($data['errors']['formRegion'][0])):?>
                <p class="noticeValidation">*<?=$data['errors']['formRegion'][0]?></p>
            <?php endif;?>
        </div>
        <div class="formItem details">
            <label for="formDetails">Detalhe do atendimento:</label>
            <textarea  <?php if(isset($data['errors']['formDetails'][0])):?>class="borderValidation"<?php endif;?>  name="formDetails" <?php if(isset($data['formRegion'])):?> value="<?=$data['formRegion']?>" <?php endif;?> placeholder="Detalhe seu atendimento"></textarea>
            <?php if(isset($data['errors']['formDetails'][0])):?>
                <p class="noticeValidation">*<?=$data['errors']['formDetails'][0]?></p>
            <?php endif;?>
        </div>
        <div class="formItem submit">
            <input type="submit" class="btn btn-medium btn-orange" value="Registrar" />
        </div>
    </form>
</div>