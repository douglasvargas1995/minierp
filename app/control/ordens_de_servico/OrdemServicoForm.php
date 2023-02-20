<?php

class OrdemServicoForm extends TPage
{
    protected $form;
    private $formFields = [];
    private static $database = 'minierp';
    private static $activeRecord = 'OrdemServico';
    private static $primaryKey = 'id';
    private static $formName = 'form_OrdemServicoForm';

    use BuilderMasterDetailTrait;
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
        $this->form->setFormTitle("Cadastro de ordem de serviço");

        $criteria_ordem_servico_atendimento_ordem_servico_tecnico_id = new TCriteria();

        $filterVar = GrupoPessoa::TECNICO;
        $criteria_ordem_servico_atendimento_ordem_servico_tecnico_id->add(new TFilter('id', 'in', "(SELECT pessoa_id FROM pessoa_grupo WHERE grupo_pessoa_id = '{$filterVar}')")); 

        $id = new TEntry('id');
        $situacao = new TEntry('situacao');
        $cliente_id = new TSeekButton('cliente_id');
        $cliente_nome = new TEntry('cliente_nome');
        $descricao = new TEntry('descricao');
        $descricao_equipamento = new TEntry('descricao_equipamento');
        $data_inicio = new TDate('data_inicio');
        $data_prevista = new TDate('data_prevista');
        $data_fim = new TDate('data_fim');
        $ordem_servico_item_ordem_servico_id = new THidden('ordem_servico_item_ordem_servico_id[]');
        $ordem_servico_item_ordem_servico___row__id = new THidden('ordem_servico_item_ordem_servico___row__id[]');
        $ordem_servico_item_ordem_servico___row__data = new THidden('ordem_servico_item_ordem_servico___row__data[]');
        $ordem_servico_item_ordem_servico_produto_tipo_produto_id = new TDBCombo('ordem_servico_item_ordem_servico_produto_tipo_produto_id[]', 'minierp', 'TipoProduto', 'id', '{nome}','nome asc'  );
        $ordem_servico_item_ordem_servico_produto_id = new TCombo('ordem_servico_item_ordem_servico_produto_id[]');
        $ordem_servico_item_ordem_servico_valor = new TNumeric('ordem_servico_item_ordem_servico_valor[]', '2', ',', '.' );
        $ordem_servico_item_ordem_servico_quantidade = new TNumeric('ordem_servico_item_ordem_servico_quantidade[]', '2', ',', '.' );
        $ordem_servico_item_ordem_servico_desconto = new TNumeric('ordem_servico_item_ordem_servico_desconto[]', '2', ',', '.' );
        $ordem_servico_item_ordem_servico_valor_total = new TNumeric('ordem_servico_item_ordem_servico_valor_total[]', '2', ',', '.' );
        $this->produtos_servicos = new TFieldList();
        $ordem_servico_atendimento_ordem_servico_tecnico_id = new TDBCombo('ordem_servico_atendimento_ordem_servico_tecnico_id', 'minierp', 'Pessoa', 'id', '{nome}','nome asc' , $criteria_ordem_servico_atendimento_ordem_servico_tecnico_id );
        $ordem_servico_atendimento_ordem_servico_id = new THidden('ordem_servico_atendimento_ordem_servico_id');
        $ordem_servico_atendimento_ordem_servico_problema_id = new TDBCombo('ordem_servico_atendimento_ordem_servico_problema_id', 'minierp', 'Problema', 'id', '{nome}','nome asc'  );
        $ordem_servico_atendimento_ordem_servico_causa_id = new TDBCombo('ordem_servico_atendimento_ordem_servico_causa_id', 'minierp', 'Causa', 'id', '{nome}','nome asc'  );
        $ordem_servico_atendimento_ordem_servico_solucao_id = new TDBCombo('ordem_servico_atendimento_ordem_servico_solucao_id', 'minierp', 'Solucao', 'id', '{nome}','nome asc'  );
        $ordem_servico_atendimento_ordem_servico_data_atendimento = new TDate('ordem_servico_atendimento_ordem_servico_data_atendimento');
        $ordem_servico_atendimento_ordem_servico_horario_inicial = new TTime('ordem_servico_atendimento_ordem_servico_horario_inicial');
        $ordem_servico_atendimento_ordem_servico_horario_final = new TTime('ordem_servico_atendimento_ordem_servico_horario_final');
        $ordem_servico_atendimento_ordem_servico_observacao = new TText('ordem_servico_atendimento_ordem_servico_observacao');
        $button_adicionar_ordem_servico_atendimento_ordem_servico = new TButton('button_adicionar_ordem_servico_atendimento_ordem_servico');
        $observacao = new TText('observacao');

        $this->produtos_servicos->addField(null, $ordem_servico_item_ordem_servico_id, []);
        $this->produtos_servicos->addField(null, $ordem_servico_item_ordem_servico___row__id, ['uniqid' => true]);
        $this->produtos_servicos->addField(null, $ordem_servico_item_ordem_servico___row__data, []);
        $this->produtos_servicos->addField(new TLabel("Tipo", null, '14px', null), $ordem_servico_item_ordem_servico_produto_tipo_produto_id, ['width' => '20%']);
        $this->produtos_servicos->addField(new TLabel("Produto", null, '14px', null), $ordem_servico_item_ordem_servico_produto_id, ['width' => '20%']);
        $this->produtos_servicos->addField(new TLabel("Valor", null, '14px', null), $ordem_servico_item_ordem_servico_valor, ['width' => '15%']);
        $this->produtos_servicos->addField(new TLabel("Quantidade", null, '14px', null), $ordem_servico_item_ordem_servico_quantidade, ['width' => '15%']);
        $this->produtos_servicos->addField(new TLabel("Desconto", null, '14px', null), $ordem_servico_item_ordem_servico_desconto, ['width' => '15%']);
        $this->produtos_servicos->addField(new TLabel("Valor total", null, '14px', null), $ordem_servico_item_ordem_servico_valor_total, ['width' => '15%','sum' => true]);

        $this->produtos_servicos->width = '100%';
        $this->produtos_servicos->setFieldPrefix('ordem_servico_item_ordem_servico');
        $this->produtos_servicos->name = 'produtos_servicos';
        $this->produtos_servicos->class .= ' table-responsive';

        $this->criteria_produtos_servicos = new TCriteria();

        $this->form->addField($ordem_servico_item_ordem_servico_id);
        $this->form->addField($ordem_servico_item_ordem_servico___row__id);
        $this->form->addField($ordem_servico_item_ordem_servico___row__data);
        $this->form->addField($ordem_servico_item_ordem_servico_produto_tipo_produto_id);
        $this->form->addField($ordem_servico_item_ordem_servico_produto_id);
        $this->form->addField($ordem_servico_item_ordem_servico_valor);
        $this->form->addField($ordem_servico_item_ordem_servico_quantidade);
        $this->form->addField($ordem_servico_item_ordem_servico_desconto);
        $this->form->addField($ordem_servico_item_ordem_servico_valor_total);

        $this->produtos_servicos->setRemoveAction(null, 'fas:times #dd5a43', "Excluír");

        $ordem_servico_item_ordem_servico_produto_tipo_produto_id->setChangeAction(new TAction([$this,'onChangeordem_servico_item_ordem_servico_produto_tipo_produto_id']));
        $ordem_servico_item_ordem_servico_produto_id->setChangeAction(new TAction([$this,'onChangeProduto']));

        $ordem_servico_item_ordem_servico_valor->setExitAction(new TAction([$this,'onExitValor']));
        $ordem_servico_item_ordem_servico_quantidade->setExitAction(new TAction([$this,'onExitQuantidade']));
        $ordem_servico_item_ordem_servico_desconto->setExitAction(new TAction([$this,'onExitDesconto']));

        $cliente_id->addValidation("Cliente id", new TRequiredValidator()); 
        $cliente_nome->addValidation("Cliente", new TRequiredValidator()); 
        $descricao->addValidation("Descrição", new TRequiredValidator()); 
        $data_inicio->addValidation("Data início", new TRequiredValidator()); 
        $ordem_servico_item_ordem_servico_produto_id->addValidation("Produto id", new TRequiredListValidator()); 

        $button_adicionar_ordem_servico_atendimento_ordem_servico->setAction(new TAction([$this, 'onAddDetailOrdemServicoAtendimentoOrdemServico'],['static' => 1]), "Adicionar");
        $data_inicio->setValue(date('d/m/Y'));
        $button_adicionar_ordem_servico_atendimento_ordem_servico->addStyleClass('btn-default');
        $button_adicionar_ordem_servico_atendimento_ordem_servico->setImage('fas:plus #2ecc71');
        $descricao->forceLowerCase();
        $descricao_equipamento->forceLowerCase();

        $data_fim->setMask('dd/mm/yyyy');
        $data_inicio->setMask('dd/mm/yyyy');
        $data_prevista->setMask('dd/mm/yyyy');
        $ordem_servico_atendimento_ordem_servico_data_atendimento->setMask('dd/mm/yyyy');

        $data_fim->setDatabaseMask('yyyy-mm-dd');
        $data_inicio->setDatabaseMask('yyyy-mm-dd');
        $data_prevista->setDatabaseMask('yyyy-mm-dd');
        $ordem_servico_atendimento_ordem_servico_data_atendimento->setDatabaseMask('yyyy-mm-dd');

        $id->setEditable(false);
        $situacao->setEditable(false);
        $data_inicio->setEditable(false);
        $cliente_nome->setEditable(false);
        $ordem_servico_item_ordem_servico_valor_total->setEditable(false);

        $ordem_servico_item_ordem_servico_produto_id->enableSearch();
        $ordem_servico_atendimento_ordem_servico_causa_id->enableSearch();
        $ordem_servico_atendimento_ordem_servico_tecnico_id->enableSearch();
        $ordem_servico_atendimento_ordem_servico_solucao_id->enableSearch();
        $ordem_servico_atendimento_ordem_servico_problema_id->enableSearch();
        $ordem_servico_item_ordem_servico_produto_tipo_produto_id->enableSearch();

        $id->setSize('100%');
        $data_fim->setSize(110);
        $situacao->setSize('100%');
        $data_inicio->setSize(110);
        $cliente_id->setSize('15%');
        $descricao->setSize('100%');
        $data_prevista->setSize(110);
        $cliente_nome->setSize('82%');
        $observacao->setSize('100%', 100);
        $descricao_equipamento->setSize('100%');
        $ordem_servico_item_ordem_servico_valor->setSize('100%');
        $ordem_servico_atendimento_ordem_servico_id->setSize(200);
        $ordem_servico_item_ordem_servico_desconto->setSize('100%');
        $ordem_servico_item_ordem_servico_produto_id->setSize('100%');
        $ordem_servico_item_ordem_servico_quantidade->setSize('100%');
        $ordem_servico_item_ordem_servico_valor_total->setSize('100%');
        $ordem_servico_atendimento_ordem_servico_causa_id->setSize('100%');
        $ordem_servico_atendimento_ordem_servico_tecnico_id->setSize('100%');
        $ordem_servico_atendimento_ordem_servico_solucao_id->setSize('100%');
        $ordem_servico_atendimento_ordem_servico_horario_final->setSize(110);
        $ordem_servico_atendimento_ordem_servico_problema_id->setSize('100%');
        $ordem_servico_atendimento_ordem_servico_horario_inicial->setSize(110);
        $ordem_servico_atendimento_ordem_servico_data_atendimento->setSize(110);
        $ordem_servico_atendimento_ordem_servico_observacao->setSize('100%', 80);
        $ordem_servico_item_ordem_servico_produto_tipo_produto_id->setSize('100%');



        $button_adicionar_ordem_servico_atendimento_ordem_servico->id = '63d87148733f4';

        $seed = AdiantiApplicationConfig::get()['general']['seed'];
        $cliente_id_seekAction = new TAction(['ClienteSeekWindow', 'onShow']);
        $seekFilters = [];
        $seekFields = base64_encode(serialize([
            ['name'=> 'cliente_id', 'column'=>'{id}'],
            ['name'=> 'cliente_nome', 'column'=>'{nome}']
        ]));

        $seekFilters = base64_encode(serialize($seekFilters));
        $cliente_id_seekAction->setParameter('_seek_fields', $seekFields);
        $cliente_id_seekAction->setParameter('_seek_filters', $seekFilters);
        $cliente_id_seekAction->setParameter('_seek_hash', md5($seed.$seekFields.$seekFilters));
        $cliente_id->setAction($cliente_id_seekAction);

        $row1 = $this->form->addFields([new TLabel("Id:", null, '14px', null, '100%'),$id],[new TLabel("Situação", null, '14px', null),$situacao]);
        $row1->layout = [' col-sm-4','col-sm-2'];

        $row2 = $this->form->addFields([new TLabel("Cliente :", '#000000', '14px', null, '100%'),$cliente_id,$cliente_nome]);
        $row2->layout = [' col-sm-12'];

        $row3 = $this->form->addFields([new TLabel("Descrição:", '#000000', '14px', null, '100%'),$descricao]);
        $row3->layout = [' col-sm-12'];

        $row4 = $this->form->addFields([new TLabel("Descrição do equipamento \ Auxiliares:", null, '14px', null, '100%'),$descricao_equipamento]);
        $row4->layout = [' col-sm-12'];

        $row5 = $this->form->addFields([new TLabel("Data início:", '#000000', '14px', null, '100%'),$data_inicio],[new TLabel("Data prevista:", null, '14px', null, '100%'),$data_prevista],[new TLabel("Data fim:", null, '14px', null, '100%'),$data_fim]);
        $row5->layout = [' col-sm-4',' col-sm-4',' col-sm-4'];

        $tab_63d84becabd8f = new BootstrapFormBuilder('tab_63d84becabd8f');
        $this->tab_63d84becabd8f = $tab_63d84becabd8f;
        $tab_63d84becabd8f->setProperty('style', 'border:none; box-shadow:none;');

        $tab_63d84becabd8f->appendPage("Produtos ou Serviços");

        $tab_63d84becabd8f->addFields([new THidden('current_tab_tab_63d84becabd8f')]);
        $tab_63d84becabd8f->setTabFunction("$('[name=current_tab_tab_63d84becabd8f]').val($(this).attr('data-current_page'));");

        $row6 = $tab_63d84becabd8f->addFields([$this->produtos_servicos]);
        $row6->layout = [' col-sm-12'];

        $tab_63d84becabd8f->appendPage("Atendimento");

        $this->detailFormOrdemServicoAtendimentoOrdemServico = new BootstrapFormBuilder('detailFormOrdemServicoAtendimentoOrdemServico');
        $this->detailFormOrdemServicoAtendimentoOrdemServico->setProperty('style', 'border:none; box-shadow:none; width:100%;');

        $this->detailFormOrdemServicoAtendimentoOrdemServico->setProperty('class', 'form-horizontal builder-detail-form');

        $row7 = $this->detailFormOrdemServicoAtendimentoOrdemServico->addFields([new TLabel("Técnico:", '#000000', '14px', null, '100%'),$ordem_servico_atendimento_ordem_servico_tecnico_id,$ordem_servico_atendimento_ordem_servico_id]);
        $row7->layout = [' col-sm-12'];

        $row8 = $this->detailFormOrdemServicoAtendimentoOrdemServico->addFields([new TLabel("Problema:", null, '14px', null, '100%'),$ordem_servico_atendimento_ordem_servico_problema_id],[new TLabel("Causa:", null, '14px', null, '100%'),$ordem_servico_atendimento_ordem_servico_causa_id],[new TLabel("Solução:", null, '14px', null, '100%'),$ordem_servico_atendimento_ordem_servico_solucao_id]);
        $row8->layout = [' col-sm-4',' col-sm-4',' col-sm-4'];

        $row9 = $this->detailFormOrdemServicoAtendimentoOrdemServico->addFields([new TLabel("Data atendimento:", null, '14px', null, '100%'),$ordem_servico_atendimento_ordem_servico_data_atendimento],[new TLabel("Horário Inicial:", null, '14px', null, '100%'),$ordem_servico_atendimento_ordem_servico_horario_inicial],[new TLabel("Horário final:", null, '14px', null, '100%'),$ordem_servico_atendimento_ordem_servico_horario_final]);
        $row9->layout = [' col-sm-4',' col-sm-4',' col-sm-4'];

        $row10 = $this->detailFormOrdemServicoAtendimentoOrdemServico->addFields([new TLabel("Observacao:", null, '14px', null, '100%'),$ordem_servico_atendimento_ordem_servico_observacao]);
        $row10->layout = [' col-sm-12'];

        $row11 = $this->detailFormOrdemServicoAtendimentoOrdemServico->addFields([$button_adicionar_ordem_servico_atendimento_ordem_servico]);
        $row11->layout = [' col-sm-12'];

        $row12 = $this->detailFormOrdemServicoAtendimentoOrdemServico->addFields([new THidden('ordem_servico_atendimento_ordem_servico__row__id')]);
        $this->ordem_servico_atendimento_ordem_servico_criteria = new TCriteria();

        $this->ordem_servico_atendimento_ordem_servico_list = new BootstrapDatagridWrapper(new TDataGrid);
        $this->ordem_servico_atendimento_ordem_servico_list->disableHtmlConversion();;
        $this->ordem_servico_atendimento_ordem_servico_list->generateHiddenFields();
        $this->ordem_servico_atendimento_ordem_servico_list->setId('ordem_servico_atendimento_ordem_servico_list');

        $this->ordem_servico_atendimento_ordem_servico_list->style = 'width:100%';
        $this->ordem_servico_atendimento_ordem_servico_list->class .= ' table-bordered';

        $column_ordem_servico_atendimento_ordem_servico_tecnico_nome = new TDataGridColumn('tecnico->nome', "Técnico", 'left');
        $column_ordem_servico_atendimento_ordem_servico_causa_nome = new TDataGridColumn('causa->nome', "Causa", 'left');
        $column_ordem_servico_atendimento_ordem_servico_problema_nome = new TDataGridColumn('problema->nome', "Problema", 'left');
        $column_ordem_servico_atendimento_ordem_servico_solucao_nome = new TDataGridColumn('solucao->nome', "Solução", 'left');
        $column_ordem_servico_atendimento_ordem_servico_data_atendimento_transformed = new TDataGridColumn('data_atendimento', "Data atendimento", 'left');
        $column_ordem_servico_atendimento_ordem_servico_horario_inicial = new TDataGridColumn('horario_inicial', "Horário Inicial", 'left');
        $column_ordem_servico_atendimento_ordem_servico_horario_final = new TDataGridColumn('horario_final', "Horário final", 'left');

        $column_ordem_servico_atendimento_ordem_servico__row__data = new TDataGridColumn('__row__data', '', 'center');
        $column_ordem_servico_atendimento_ordem_servico__row__data->setVisibility(false);

        $action_onEditDetailOrdemServicoAtendimento = new TDataGridAction(array('OrdemServicoForm', 'onEditDetailOrdemServicoAtendimento'));
        $action_onEditDetailOrdemServicoAtendimento->setUseButton(false);
        $action_onEditDetailOrdemServicoAtendimento->setButtonClass('btn btn-default btn-sm');
        $action_onEditDetailOrdemServicoAtendimento->setLabel("Editar");
        $action_onEditDetailOrdemServicoAtendimento->setImage('far:edit #478fca');
        $action_onEditDetailOrdemServicoAtendimento->setFields(['__row__id', '__row__data']);

        $this->ordem_servico_atendimento_ordem_servico_list->addAction($action_onEditDetailOrdemServicoAtendimento);
        $action_onDeleteDetailOrdemServicoAtendimento = new TDataGridAction(array('OrdemServicoForm', 'onDeleteDetailOrdemServicoAtendimento'));
        $action_onDeleteDetailOrdemServicoAtendimento->setUseButton(false);
        $action_onDeleteDetailOrdemServicoAtendimento->setButtonClass('btn btn-default btn-sm');
        $action_onDeleteDetailOrdemServicoAtendimento->setLabel("Excluir");
        $action_onDeleteDetailOrdemServicoAtendimento->setImage('fas:trash-alt #dd5a43');
        $action_onDeleteDetailOrdemServicoAtendimento->setFields(['__row__id', '__row__data']);

        $this->ordem_servico_atendimento_ordem_servico_list->addAction($action_onDeleteDetailOrdemServicoAtendimento);

        $this->ordem_servico_atendimento_ordem_servico_list->addColumn($column_ordem_servico_atendimento_ordem_servico_tecnico_nome);
        $this->ordem_servico_atendimento_ordem_servico_list->addColumn($column_ordem_servico_atendimento_ordem_servico_causa_nome);
        $this->ordem_servico_atendimento_ordem_servico_list->addColumn($column_ordem_servico_atendimento_ordem_servico_problema_nome);
        $this->ordem_servico_atendimento_ordem_servico_list->addColumn($column_ordem_servico_atendimento_ordem_servico_solucao_nome);
        $this->ordem_servico_atendimento_ordem_servico_list->addColumn($column_ordem_servico_atendimento_ordem_servico_data_atendimento_transformed);
        $this->ordem_servico_atendimento_ordem_servico_list->addColumn($column_ordem_servico_atendimento_ordem_servico_horario_inicial);
        $this->ordem_servico_atendimento_ordem_servico_list->addColumn($column_ordem_servico_atendimento_ordem_servico_horario_final);

        $this->ordem_servico_atendimento_ordem_servico_list->addColumn($column_ordem_servico_atendimento_ordem_servico__row__data);

        $this->ordem_servico_atendimento_ordem_servico_list->createModel();
        $tableResponsiveDiv = new TElement('div');
        $tableResponsiveDiv->class = 'table-responsive';
        $tableResponsiveDiv->add($this->ordem_servico_atendimento_ordem_servico_list);
        $this->detailFormOrdemServicoAtendimentoOrdemServico->addContent([$tableResponsiveDiv]);

        $column_ordem_servico_atendimento_ordem_servico_data_atendimento_transformed->setTransformer(function($value, $object, $row, $cell = null, $last_row = null)
        {
            if(!empty(trim($value)))
            {
                try
                {
                    $date = new DateTime($value);
                    return $date->format('d/m/Y');
                }
                catch (Exception $e)
                {
                    return $value;
                }
            }
        });        $row13 = $tab_63d84becabd8f->addFields([$this->detailFormOrdemServicoAtendimentoOrdemServico]);
        $row13->layout = [' col-sm-12'];

        $row14 = $this->form->addFields([$tab_63d84becabd8f]);
        $row14->layout = [' col-sm-12'];

        $row15 = $this->form->addFields([new TLabel("Observação:", null, '14px', null, '100%'),$observacao]);
        $row15->layout = [' col-sm-12'];

        // create the form actions
        $btn_onsave = $this->form->addAction("Salvar", new TAction([$this, 'onSave'],['static' => 1]), 'fas:save #ffffff');
        $this->btn_onsave = $btn_onsave;
        $btn_onsave->addStyleClass('btn-primary'); 

        $btn_onclear = $this->form->addAction("Limpar formulário", new TAction([$this, 'onClear']), 'fas:eraser #dd5a43');
        $this->btn_onclear = $btn_onclear;

        $btn_onshow = $this->form->addAction("Voltar", new TAction(['OrdemServicoList', 'onShow']), 'fas:arrow-left #000000');
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

        $style = new TStyle('right-panel > .container-part[page-name=OrdemServicoForm]');
        $style->width = '70% !important';   
        $style->show(true);

    }

    public static function onChangeordem_servico_item_ordem_servico_produto_tipo_produto_id($param)
    {
        try
        {

            $field_id = explode('_', $param['_field_id']);
            $field_id = end($field_id);

            if (!empty($param['key']))
            { 
                $criteria = TCriteria::create(['tipo_produto_id' => $param['key']]);
                TDBCombo::reloadFromModel(self::$formName, 'ordem_servico_item_ordem_servico_produto_id_'.$field_id, 'minierp', 'Produto', 'id', '{nome}', 'nome asc', $criteria, TRUE); 
            } 
            else 
            { 
                TCombo::clearField(self::$formName, 'ordem_servico_item_ordem_servico_produto_id_'.$field_id); 
            }  

        }
        catch (Exception $e)
        {
            new TMessage('error', $e->getMessage());
        }
    } 

    public static function onExitValor($param = null) 
    {
        try 
        {
            self::onExitQuantidade($param);

        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }

    public static function onExitQuantidade($param = null) 
    {
        try 
        {

            // Código gerado pelo snippet: "Cálculos com campos"
            $field_id = explode('_', $param['_field_id']);
            $field_id = end($field_id);
            $field_data = json_decode($param['_field_data_json']);

            $ordem_servico_item_ordem_servico_quantidade = (double) str_replace(',', '.', str_replace('.', '', $param['ordem_servico_item_ordem_servico_quantidade'][$field_data->row] ?? 0));
            $ordem_servico_item_ordem_servico_valor = (double) str_replace(',', '.', str_replace('.', '', $param['ordem_servico_item_ordem_servico_valor'][$field_data->row] ?? 0));
            $ordem_servico_item_ordem_servico_desconto = (double) str_replace(',', '.', str_replace('.', '', $param['ordem_servico_item_ordem_servico_desconto'][$field_data->row] ?? 0));

            $ordem_servico_item_ordem_servico_valor_total = ( $ordem_servico_item_ordem_servico_quantidade * $ordem_servico_item_ordem_servico_valor ) - $ordem_servico_item_ordem_servico_desconto ;
            $object = new stdClass();
            $object->{"ordem_servico_item_ordem_servico_valor_total_{$field_id}"} = number_format($ordem_servico_item_ordem_servico_valor_total, 2, ',', '.');
            TForm::sendData(self::$formName, $object);
            // -----

        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }

    public static function onExitDesconto($param = null) 
    {
        try 
        {

            self::onExitQuantidade($param);

        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }

    public static function onChangeProduto($param = null) 
    {
        try 
        {

            if(!empty($param['key']))
            {

            TTransaction::open('minierp');

            $produto = Produto::find($param['key']);

            // Código gerado pelo snippet: "Enviar dados para campo"

            $field_id = explode('_', $param['_field_id']);
            $field_id = end($field_id);

            $object = new stdClass();
            $object->{"ordem_servico_item_ordem_servico_valor_{$field_id}"} = number_format($produto->preco_venda, 2, ',', '.');

            TForm::sendData(self::$formName, $object);

            }
            TTransaction::close();

        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }

    public  function onAddDetailOrdemServicoAtendimentoOrdemServico($param = null) 
    {
        try
        {
            $data = $this->form->getData();

            $errors = [];
            $requiredFields = [];
            $requiredFields[] = ['label'=>"Técnico", 'name'=>"ordem_servico_atendimento_ordem_servico_tecnico_id", 'class'=>'TRequiredValidator', 'value'=>[]];
            foreach($requiredFields as $requiredField)
            {
                try
                {
                    (new $requiredField['class'])->validate($requiredField['label'], $data->{$requiredField['name']}, $requiredField['value']);
                }
                catch(Exception $e)
                {
                    $errors[] = $e->getMessage() . '.';
                }
             }
             if(count($errors) > 0)
             {
                 throw new Exception(implode('<br>', $errors));
             }

            $__row__id = !empty($data->ordem_servico_atendimento_ordem_servico__row__id) ? $data->ordem_servico_atendimento_ordem_servico__row__id : 'b'.uniqid();

            TTransaction::open(self::$database);

            $grid_data = new OrdemServicoAtendimento();
            $grid_data->__row__id = $__row__id;
            $grid_data->tecnico_id = $data->ordem_servico_atendimento_ordem_servico_tecnico_id;
            $grid_data->id = $data->ordem_servico_atendimento_ordem_servico_id;
            $grid_data->problema_id = $data->ordem_servico_atendimento_ordem_servico_problema_id;
            $grid_data->causa_id = $data->ordem_servico_atendimento_ordem_servico_causa_id;
            $grid_data->solucao_id = $data->ordem_servico_atendimento_ordem_servico_solucao_id;
            $grid_data->data_atendimento = $data->ordem_servico_atendimento_ordem_servico_data_atendimento;
            $grid_data->horario_inicial = $data->ordem_servico_atendimento_ordem_servico_horario_inicial;
            $grid_data->horario_final = $data->ordem_servico_atendimento_ordem_servico_horario_final;
            $grid_data->observacao = $data->ordem_servico_atendimento_ordem_servico_observacao;

            $__row__data = array_merge($grid_data->toArray(), (array)$grid_data->getVirtualData());
            $__row__data['__row__id'] = $__row__id;
            $__row__data['__display__']['tecnico_id'] =  $param['ordem_servico_atendimento_ordem_servico_tecnico_id'] ?? null;
            $__row__data['__display__']['id'] =  $param['ordem_servico_atendimento_ordem_servico_id'] ?? null;
            $__row__data['__display__']['problema_id'] =  $param['ordem_servico_atendimento_ordem_servico_problema_id'] ?? null;
            $__row__data['__display__']['causa_id'] =  $param['ordem_servico_atendimento_ordem_servico_causa_id'] ?? null;
            $__row__data['__display__']['solucao_id'] =  $param['ordem_servico_atendimento_ordem_servico_solucao_id'] ?? null;
            $__row__data['__display__']['data_atendimento'] =  $param['ordem_servico_atendimento_ordem_servico_data_atendimento'] ?? null;
            $__row__data['__display__']['horario_inicial'] =  $param['ordem_servico_atendimento_ordem_servico_horario_inicial'] ?? null;
            $__row__data['__display__']['horario_final'] =  $param['ordem_servico_atendimento_ordem_servico_horario_final'] ?? null;
            $__row__data['__display__']['observacao'] =  $param['ordem_servico_atendimento_ordem_servico_observacao'] ?? null;

            $grid_data->__row__data = base64_encode(serialize((object)$__row__data));
            $row = $this->ordem_servico_atendimento_ordem_servico_list->addItem($grid_data);
            $row->id = $grid_data->__row__id;

            TDataGrid::replaceRowById('ordem_servico_atendimento_ordem_servico_list', $grid_data->__row__id, $row);

            TTransaction::close();

            $data = new stdClass;
            $data->ordem_servico_atendimento_ordem_servico_tecnico_id = '';
            $data->ordem_servico_atendimento_ordem_servico_id = '';
            $data->ordem_servico_atendimento_ordem_servico_problema_id = '';
            $data->ordem_servico_atendimento_ordem_servico_causa_id = '';
            $data->ordem_servico_atendimento_ordem_servico_solucao_id = '';
            $data->ordem_servico_atendimento_ordem_servico_data_atendimento = '';
            $data->ordem_servico_atendimento_ordem_servico_horario_inicial = '';
            $data->ordem_servico_atendimento_ordem_servico_horario_final = '';
            $data->ordem_servico_atendimento_ordem_servico_observacao = '';
            $data->ordem_servico_atendimento_ordem_servico__row__id = '';

            TForm::sendData(self::$formName, $data);
            TScript::create("
               var element = $('#63d87148733f4');
               if(typeof element.attr('add') != 'undefined')
               {
                   element.html(base64_decode(element.attr('add')));
               }
            ");

        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage()); // shows the exception error message
        }
    }

    public static function onEditDetailOrdemServicoAtendimento($param = null) 
    {
        try
        {

            $__row__data = unserialize(base64_decode($param['__row__data']));
            $__row__data->__display__ = is_array($__row__data->__display__) ? (object) $__row__data->__display__ : $__row__data->__display__;

            $data = new stdClass;
            $data->ordem_servico_atendimento_ordem_servico_tecnico_id = $__row__data->__display__->tecnico_id ?? null;
            $data->ordem_servico_atendimento_ordem_servico_id = $__row__data->__display__->id ?? null;
            $data->ordem_servico_atendimento_ordem_servico_problema_id = $__row__data->__display__->problema_id ?? null;
            $data->ordem_servico_atendimento_ordem_servico_causa_id = $__row__data->__display__->causa_id ?? null;
            $data->ordem_servico_atendimento_ordem_servico_solucao_id = $__row__data->__display__->solucao_id ?? null;
            $data->ordem_servico_atendimento_ordem_servico_data_atendimento = $__row__data->__display__->data_atendimento ?? null;
            $data->ordem_servico_atendimento_ordem_servico_horario_inicial = $__row__data->__display__->horario_inicial ?? null;
            $data->ordem_servico_atendimento_ordem_servico_horario_final = $__row__data->__display__->horario_final ?? null;
            $data->ordem_servico_atendimento_ordem_servico_observacao = $__row__data->__display__->observacao ?? null;
            $data->ordem_servico_atendimento_ordem_servico__row__id = $__row__data->__row__id;

            TForm::sendData(self::$formName, $data);
            TScript::create("
               var element = $('#63d87148733f4');
               if(!element.attr('add')){
                   element.attr('add', base64_encode(element.html()));
               }
               element.html(\"<span><i class='far fa-edit' style='color:#478fca;padding-right:4px;'></i>Editar</span>\");
               if(!element.attr('edit')){
                   element.attr('edit', base64_encode(element.html()));
               }
            ");

        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage()); // shows the exception error message
        }
    }
    public static function onDeleteDetailOrdemServicoAtendimento($param = null) 
    {
        try
        {

            $__row__data = unserialize(base64_decode($param['__row__data']));

            $data = new stdClass;
            $data->ordem_servico_atendimento_ordem_servico_tecnico_id = '';
            $data->ordem_servico_atendimento_ordem_servico_id = '';
            $data->ordem_servico_atendimento_ordem_servico_problema_id = '';
            $data->ordem_servico_atendimento_ordem_servico_causa_id = '';
            $data->ordem_servico_atendimento_ordem_servico_solucao_id = '';
            $data->ordem_servico_atendimento_ordem_servico_data_atendimento = '';
            $data->ordem_servico_atendimento_ordem_servico_horario_inicial = '';
            $data->ordem_servico_atendimento_ordem_servico_horario_final = '';
            $data->ordem_servico_atendimento_ordem_servico_observacao = '';
            $data->ordem_servico_atendimento_ordem_servico__row__id = '';

            TForm::sendData(self::$formName, $data);

            TDataGrid::removeRowById('ordem_servico_atendimento_ordem_servico_list', $__row__data->__row__id);

        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage()); // shows the exception error message
        }
    }
    public function onSave($param = null) 
    {
        try
        {
            TTransaction::open(self::$database); // open a transaction

            $messageAction = null;

            $this->form->validate(); // validate form data

            $object = new OrdemServico(); // create an empty object 

            $data = $this->form->getData(); // get form data as array
            $object->fromArray( (array) $data); // load the object with data

            $object->valor_total = 0;

            if(!$data->id)
            {
            	$object->mes = date('m');
	            $object->ano = date('Y');
            	$object->mes_ano = date('m/Y');
            }

            if($object->data_fim)
            {
                $object->situacao = 'FINALIZADO';
            }else
            {
                $object->situacao = 'EM ABERTO';
            }

            $object->store(); // save the object 

            $this->fireEvents($object);

            $loadPageParam = [];

            if(!empty($param['target_container']))
            {
                $loadPageParam['target_container'] = $param['target_container'];
            }

            $ordem_servico_atendimento_ordem_servico_items = $this->storeMasterDetailItems('OrdemServicoAtendimento', 'ordem_servico_id', 'ordem_servico_atendimento_ordem_servico', $object, $param['ordem_servico_atendimento_ordem_servico_list___row__data'] ?? [], $this->form, $this->ordem_servico_atendimento_ordem_servico_list, function($masterObject, $detailObject){ 

                //code here

            }, $this->ordem_servico_atendimento_ordem_servico_criteria); 

            $ordem_servico_item_ordem_servico_items = $this->storeItems('OrdemServicoItem', 'ordem_servico_id', $object, $this->produtos_servicos, function($masterObject, $detailObject){ 

            $masterObject->valor_total += $detailObject->valor_total;

            }, $this->criteria_produtos_servicos); 

            $object->store();

            // get the generated {PRIMARY_KEY}
            $data->id = $object->id; 

            $this->form->setData($data); // fill form data
            TTransaction::close(); // close the transaction

            TToast::show('success', "Registro salvo", 'topRight', 'far:check-circle');
            TApplication::loadPage('OrdemServicoList', 'onShow', $loadPageParam); 

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

                $object = new OrdemServico($key); // instantiates the Active Record 

                                $object->cliente_nome = $object->cliente->nome;

                $ordem_servico_atendimento_ordem_servico_items = $this->loadMasterDetailItems('OrdemServicoAtendimento', 'ordem_servico_id', 'ordem_servico_atendimento_ordem_servico', $object, $this->form, $this->ordem_servico_atendimento_ordem_servico_list, $this->ordem_servico_atendimento_ordem_servico_criteria, function($masterObject, $detailObject, $objectItems){ 

                    //code here

                }); 

                $this->produtos_servicos_items = $this->loadItems('OrdemServicoItem', 'ordem_servico_id', $object, $this->produtos_servicos, function($masterObject, $detailObject, $objectItems){ 

                    //code here

                }, $this->criteria_produtos_servicos); 

                $this->form->setData($object); // fill the form 

                $this->fireEvents($object);

                if($this->produtos_servicos_items)
                {
                    $fieldListData = new stdClass();
                    $fieldListData->ordem_servico_item_ordem_servico_produto_tipo_produto_id = [];
                    $fieldListData->ordem_servico_item_ordem_servico_produto_id = [];

                    foreach ($this->produtos_servicos_items as $item) 
                    {
                        if(isset($item->produto->tipo_produto_id))
                        {
                            $value = $item->produto->tipo_produto_id;

                            $fieldListData->ordem_servico_item_ordem_servico_produto_tipo_produto_id[] = $value;
                        }
                        if(isset($item->produto_id))
                        {
                            $value = $item->produto_id;

                            $fieldListData->ordem_servico_item_ordem_servico_produto_id[] = $value;
                        }
                    }

                    TScript::create('tjquerydialog_block_ui(); tform_events_stop( function() {tjquerydialog_unblock_ui()} );');

                    TForm::sendData(self::$formName, $fieldListData);
                }

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

        $this->produtos_servicos->addHeader();
        $this->produtos_servicos->addDetail( new stdClass );

        $this->produtos_servicos->addCloneAction(null, 'fas:plus #69aa46', "Clonar");

    }

    public function onShow($param = null)
    {
        $this->produtos_servicos->addHeader();
        $this->produtos_servicos->addDetail( new stdClass );

        $this->produtos_servicos->addCloneAction(null, 'fas:plus #69aa46', "Clonar");

    } 

    public function fireEvents( $object )
    {
        $obj = new stdClass;
        if(is_object($object) && get_class($object) == 'stdClass')
        {
            if(isset($object->cliente_id))
            {
                $value = $object->cliente_id;

                $obj->cliente_id = $value;
            }
        }
        elseif(is_object($object))
        {
            if(isset($object->cliente_id))
            {
                $value = $object->cliente_id;

                $obj->cliente_id = $value;
            }
        }
        TForm::sendData(self::$formName, $obj);
    }  

}

