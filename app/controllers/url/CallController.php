<?php
class CallController extends Controller{

    public function __construct(){}

    public function indexAction(){
        $Call = new Call();
        $data['Calls'] = $Call->getGroupedByDate();
        return $data;
    }

    public function formAction($data){
        $Region = new Region();
        $data['regions'] = $Region->getAll();
        return $data;
    }

    public function showAction($data){
        $Call = new Call();
        $data = $Call->getByID($data['callID']);
        return $data;
    }

    public function saveAction($data){
        $this->validate($data,'formDetails','isEmpty');
        $this->validate($data,'formReason','isEmpty');
        $this->validate($data,'formRegion','isEmpty');
        $this->validate($data,'formType','isEmpty');

        if(!$this->getErrors()){
            $Region = new Region();
            $Region = $Region->getById($data['formRegion']);
            if(!empty($Region->getId())){
                $Call = new Call($data['formType'], $data['formReason'], $Region, $data['formDetails']);
                if($Call->save()){
                    $this->setFeedback('success','Chamada registrada com sucesso!');
                }else{
                    $this->setFeedback('error','Ops! Alguma coisa deu errado!');
                }
                $this->redirect("/atendimentos");
            }else{
                $this->setFeedback('error','Ops! Alguma coisa deu errado!');
            }
        }else{
            $this->setFeedback('error','Me desculpe, verifique os campos abaixo');
        }
    }
}
?>