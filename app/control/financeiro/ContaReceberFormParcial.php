<?php

class ContaReceberFormParcial extends TPage
{
    protected $form;
    private $formFields = [];
    private static $database = 'minierp';
    private static $activeRecord = 'Conta';
    private static $primaryKey = 'id';
    private static $formName = 'form_ContaReceberForm';

    use BuilderMasterDetailFieldListTrait;

    /**
     * Form constructor
     * @param $param Request
     */
    public function __construct( $param )
    {
        parent::__construct();

        if(!empty($param['target_container']))
        {
            $this->adianti_target_container = $param['target_container'];
        }

        // creates the form
        $this->form = new BootstrapFormBuilder(self::$formName);
        // define the form title
        $this->form->setFormTitle("Cadastro de conta a receber parcial");

        $criteria_pessoa_id = new TCriteria();
        $criteria_categoria_id = new TCriteria();

        $filterVar = GrupoPessoa::CLIENTE;
        $criteria_pessoa_id->add(new TFilter('id', 'in', "(SELECT pessoa_id FROM pessoa_grupo WHERE grupo_pessoa_id = '{$filterVar}')")); 
        $filterVar = TipoConta::RECEBER;
        $criteria_categoria_id->add(new TFilter('tipo_conta_id', '=', $filterVar)); 

        $id = new TEntry('id');
        $valor = new TNumeric('valor', '2', ',', '.' );
        $valor_recebido = new TNumeric('valor_recebido', '2', ',', '.' );
        $valor_diferenca = new TNumeric('valor_diferenca', '2', ',', '.' );
        $pessoa_id = new TDBCombo('pessoa_id', 'minierp', 'Pessoa', 'id', '{nome}','nome asc' , $criteria_pessoa_id );
        $categoria_id = new TDBCombo('categoria_id', 'minierp', 'Categoria', 'id', '{nome}','nome asc' , $criteria_categoria_id );
        $dt_emissao = new TDate('dt_emissao');
        $dt_pagamento = new TDate('dt_pagamento');
        $dt_vencimento = new TDate('dt_vencimento');
        $item_pagamento_conta_id = new THidden('item_pagamento_conta_id[]');
        $item_pagamento_conta___row__id = new THidden('item_pagamento_conta___row__id[]');
        $item_pagamento_conta___row__data = new THidden('item_pagamento_conta___row__data[]');
        $item_pagamento_conta_forma_pagamento_id = new TDBCombo('item_pagamento_conta_forma_pagamento_id[]', 'minierp', 'FormaPagamento', 'id', '{nome}','nome asc'  );
        $item_pagamento_conta_obs = new TEntry('item_pagamento_conta_obs[]');
        $item_pagamento_conta_valor = new TNumeric('item_pagamento_conta_valor[]', '2', ',', '.' );
        $item_pagamento_conta_created_at = new TDateTime('item_pagamento_conta_created_at[]');
        $this->fieldList_63f907cba0853 = new TFieldList();

        $this->fieldList_63f907cba0853->addField(null, $item_pagamento_conta_id, []);
        $this->fieldList_63f907cba0853->addField(null, $item_pagamento_conta___row__id, ['uniqid' => true]);
        $this->fieldList_63f907cba0853->addField(null, $item_pagamento_conta___row__data, []);
        $this->fieldList_63f907cba0853->addField(new TLabel("Forma pagamento", null, '14px', null), $item_pagamento_conta_forma_pagamento_id, ['width' => '25%']);
        $this->fieldList_63f907cba0853->addField(new TLabel("Observação", null, '14px', null), $item_pagamento_conta_obs, ['width' => '25%']);
        $this->fieldList_63f907cba0853->addField(new TLabel("Valor", null, '14px', null), $item_pagamento_conta_valor, ['width' => '25%','sum' => true]);
        $this->fieldList_63f907cba0853->addField(new TLabel("Criado em", null, '14px', null), $item_pagamento_conta_created_at, ['width' => '25%']);

        $this->fieldList_63f907cba0853->width = '100%';
        $this->fieldList_63f907cba0853->setFieldPrefix('item_pagamento_conta');
        $this->fieldList_63f907cba0853->name = 'fieldList_63f907cba0853';
        $this->fieldList_63f907cba0853->class .= ' table-responsive';

        $this->criteria_fieldList_63f907cba0853 = new TCriteria();

        $this->form->addField($item_pagamento_conta_id);
        $this->form->addField($item_pagamento_conta___row__id);
        $this->form->addField($item_pagamento_conta___row__data);
        $this->form->addField($item_pagamento_conta_forma_pagamento_id);
        $this->form->addField($item_pagamento_conta_obs);
        $this->form->addField($item_pagamento_conta_valor);
        $this->form->addField($item_pagamento_conta_created_at);

        $this->fieldList_63f907cba0853->setRemoveAction(null, 'fas:times #dd5a43', "Excluír");

        $pessoa_id->addValidation("Pessoa", new TRequiredValidator()); 
        $categoria_id->addValidation("Categoria", new TRequiredValidator()); 
        $item_pagamento_conta_forma_pagamento_id->addValidation("Forma pagamento id", new TRequiredListValidator()); 

        $valor_diferenca->setAllowNegative(false);
        $item_pagamento_conta_valor->setAllowNegative(false);

        $valor->setValue('0,00');
        $valor_recebido->setValue('0,00');
        $valor_diferenca->setValue('0,00');

        $pessoa_id->enableSearch();
        $categoria_id->enableSearch();
        $item_pagamento_conta_forma_pagamento_id->enableSearch();

        $dt_emissao->setMask('dd/mm/yyyy');
        $dt_pagamento->setMask('dd/mm/yyyy');
        $dt_vencimento->setMask('dd/mm/yyyy');
        $item_pagamento_conta_created_at->setMask('dd/mm/yyyy hh:ii');

        $dt_emissao->setDatabaseMask('yyyy-mm-dd');
        $dt_pagamento->setDatabaseMask('yyyy-mm-dd');
        $dt_vencimento->setDatabaseMask('yyyy-mm-dd');
        $item_pagamento_conta_created_at->setDatabaseMask('yyyy-mm-dd hh:ii');

        $id->setEditable(false);
        $valor->setEditable(false);
        $pessoa_id->setEditable(false);
        $dt_emissao->setEditable(false);
        $categoria_id->setEditable(false);
        $dt_pagamento->setEditable(false);
        $dt_vencimento->setEditable(false);
        $valor_recebido->setEditable(false);
        $valor_diferenca->setEditable(false);
        $item_pagamento_conta_created_at->setEditable(false);

        $id->setSize(100);
        $valor->setSize('100%');
        $pessoa_id->setSize('100%');
        $dt_pagamento->setSize(110);
        $dt_emissao->setSize('100%');
        $dt_vencimento->setSize(110);
        $categoria_id->setSize('100%');
        $valor_recebido->setSize('100%');
        $valor_diferenca->setSize('100%');
        $item_pagamento_conta_obs->setSize('100%');
        $item_pagamento_conta_valor->setSize('100%');
        $item_pagamento_conta_created_at->setSize(150);
        $item_pagamento_conta_forma_pagamento_id->setSize('100%');


        $row1 = $this->form->addFields([new TLabel("Id:", null, '14px', null, '100%'),$id],[new TLabel("Valor total:", null, '14px', null, '100%'),$valor],[new TLabel("Valor recebido:", null, '14px', null, '100%'),$valor_recebido],[new TLabel("Valor restante", null, '14px', null),$valor_diferenca]);
        $row1->layout = [' col-sm-3','col-sm-3','col-sm-3',' col-sm-3'];

        $row2 = $this->form->addFields([new TLabel("Cliente:", null, '14px', null, '100%'),$pessoa_id],[new TLabel("Categoria:", null, '14px', null, '100%'),$categoria_id]);
        $row2->layout = ['col-sm-6','col-sm-6'];

        $row3 = $this->form->addFields([new TLabel("Data de emissão:", null, '14px', null, '100%'),$dt_emissao],[new TLabel("Data de pagamento:", null, '14px', null, '100%'),$dt_pagamento],[new TLabel("Data de vencimento:", null, '14px', null, '100%'),$dt_vencimento]);
        $row3->layout = ['col-sm-4','col-sm-4','col-sm-4'];

        $row4 = $this->form->addFields([$this->fieldList_63f907cba0853]);
        $row4->layout = [' col-sm-12'];

        // create the form actions
        $btn_onsave = $this->form->addAction("Salvar", new TAction([$this, 'onSave'],['static' => 1]), 'fas:save #ffffff');
        $this->btn_onsave = $btn_onsave;
        $btn_onsave->addStyleClass('btn-primary'); 

        $btn_onclear = $this->form->addAction("Limpar formulário", new TAction([$this, 'onClear']), 'fas:eraser #dd5a43');
        $this->btn_onclear = $btn_onclear;

        $btn_onshow = $this->form->addAction("Voltar", new TAction(['ContaPagarList', 'onShow']), 'fas:arrow-left #000000');
        $this->btn_onshow = $btn_onshow;

        parent::setTargetContainer('adianti_right_panel');

        $btnClose = new TButton('closeCurtain');
        $btnClose->class = 'btn btn-sm btn-default';
        $btnClose->style = 'margin-right:10px;';
        $btnClose->onClick = "Template.closeRightPanel();";
        $btnClose->setLabel("Fechar");
        $btnClose->setImage('fas:times');

        $this->form->addHeaderWidget($btnClose);

        parent::add($this->form);

    }

    public function onSave($param = null) 
    {
        try
        {

            TTransaction::open(self::$database); // open a transaction

            $messageAction = null;

            $this->form->validate(); // validate form data

            $object = new Conta(); // create an empty object 

            $data = $this->form->getData(); // get form data as array
            $object->fromArray( (array) $data); // load the object with data

            $object->valor_recebido = 0;
            $object->valor_diferenca = 0;
            $object->tipo_conta_id = TipoConta::RECEBER;

            $object->store(); // save the object 

            $loadPageParam = [];

            if(!empty($param['target_container']))
            {
                $loadPageParam['target_container'] = $param['target_container'];
            }

            $item_pagamento_conta_items = $this->storeItems('ItemPagamento', 'conta_id', $object, $this->fieldList_63f907cba0853, function($masterObject, $detailObject){ 

            $detailObject->mes = date('m');
            $detailObject->ano = date('Y');

            }, $this->criteria_fieldList_63f907cba0853); 

            // get the generated {PRIMARY_KEY}
            $data->id = $object->id; 

            $this->form->setData($data); // fill form data
            TTransaction::close(); // close the transaction

            TToast::show('success', "Registro salvo", 'topRight', 'far:check-circle');
            TApplication::loadPage('ContaReceberList', 'onShow', $loadPageParam); 

                        TScript::create("Template.closeRightPanel();");
            TForm::sendData(self::$formName, (object)['id' => $object->id]);

        }
        catch (Exception $e) // in case of exception
        {
            //</catchAutoCode> 

            new TMessage('error', $e->getMessage()); // shows the exception error message
            $this->form->setData( $this->form->getData() ); // keep form data
            TTransaction::rollback(); // undo all pending operations
        }
    }

    public function onEdit( $param )
    {
        try
        {
            if (isset($param['key']))
            {
                $key = $param['key'];  // get the parameter $key
                TTransaction::open(self::$database); // open a transaction

                $object = new Conta($key); // instantiates the Active Record 

                $this->fieldList_63f907cba0853_items = $this->loadItems('ItemPagamento', 'conta_id', $object, $this->fieldList_63f907cba0853, function($masterObject, $detailObject, $objectItems){ 

                    $masterObject->valor_recebido += $detailObject->valor;
                    $masterObject->valor_diferenca = $masterObject->valor - $masterObject->valor_recebido;

                }, $this->criteria_fieldList_63f907cba0853); 

                $this->form->setData($object); // fill the form 

                TTransaction::close(); // close the transaction 
            }
            else
            {
                $this->form->clear();
            }
        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage()); // shows the exception error message
            TTransaction::rollback(); // undo all pending operations
        }
    }

    /**
     * Clear form data
     * @param $param Request
     */
    public function onClear( $param )
    {
        $this->form->clear(true);

        $this->fieldList_63f907cba0853->addHeader();
        $this->fieldList_63f907cba0853->addDetail( new stdClass );

        $this->fieldList_63f907cba0853->addCloneAction(null, 'fas:plus #69aa46', "Clonar");

    }

    public function onShow($param = null)
    {
        $this->fieldList_63f907cba0853->addHeader();
        $this->fieldList_63f907cba0853->addDetail( new stdClass );

        $this->fieldList_63f907cba0853->addCloneAction(null, 'fas:plus #69aa46', "Clonar");

        //disable campo data emissao

    } 

    public  function onQuitarParcial($param = null) 
    {
        try 
        {

        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }

}

