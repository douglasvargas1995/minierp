<?php

class OrdemServicoFormViewPublico extends TPage
{
    protected $form; // form
    private static $database = 'minierp';
    private static $activeRecord = 'OrdemServico';
    private static $primaryKey = 'id';
    private static $formName = 'formView_OrdemServico';

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

        TTransaction::open(self::$database);
        // creates the form
        $this->form = new BootstrapFormBuilder(self::$formName);
        $this->form->setTagName('div');

        $ordem_servico = new OrdemServico($param['key']);
        // define the form title
        $this->form->setFormTitle("Consulta da OS");

        $label2 = new TLabel(new TImage('fas:code #000000')."Código da OS:", '', '14px', 'B', '100%');
        $text1 = new TTextDisplay($ordem_servico->id, '', '16px', '');
        $label3 = new TLabel("Situação:", '', '14px', 'B', '100%');
        $text77 = new TTextDisplay($ordem_servico->situacao, '', '16px', '');
        $label4 = new TLabel(new TImage('fas:calendar-alt #000000')."Criado em:", '', '14px', 'B', '100%');
        $text11 = new TTextDisplay(TDateTime::convertToMask($ordem_servico->created_at, 'yyyy-mm-dd hh:ii', 'dd/mm/yyyy hh:ii'), '', '16px', '');
        $label6 = new TLabel(new TImage('fas:calendar-alt #000000')."Data início:", '', '14px', 'B', '100%');
        $text4 = new TTextDisplay(TDate::convertToMask($ordem_servico->data_inicio, 'yyyy-mm-dd', 'dd/mm/yyyy'), '', '16px', '');
        $label8 = new TLabel(new TImage('fas:calendar-alt #000000')."Data prevista:", '', '14px', 'B', '100%');
        $text7 = new TTextDisplay(TDate::convertToMask($ordem_servico->data_prevista, 'yyyy-mm-dd', 'dd/mm/yyyy'), '', '16px', '');
        $label10 = new TLabel(new TImage('fas:calendar-alt #000000')."Data fim:", '', '14px', 'B', '100%');
        $text6 = new TTextDisplay(TDate::convertToMask($ordem_servico->data_fim, 'yyyy-mm-dd', 'dd/mm/yyyy'), '', '16px', '');
        $label13 = new TLabel(new TImage('fas:address-book #000000')."Cliente:", '', '14px', 'B', '100%');
        $text2 = new TTextDisplay($ordem_servico->cliente->nome, '', '16px', '');
        $label15 = new TLabel(new TImage('fas:align-justify #000000')."Descricao:", '', '14px', 'B', '100%');
        $text3 = new TTextDisplay($ordem_servico->descricao, '', '16px', '');
        $label17 = new TLabel(new TImage('far:clipboard #000000')."Descrição do equipamento | Auxiliares:", '', '14px', 'B', '100%');
        $text5 = new TTextDisplay($ordem_servico->descricao_equipamento, '', '16px', '');
        $label21 = new TLabel("Valor total:", '', '14px', 'B', '100%');
        $text10 = new TTextDisplay(number_format($ordem_servico->valor_total, '2', ',', '.'), '', '16px', '');
        $label19 = new TLabel("Observação:", '', '14px', 'B', '100%');
        $text9 = new TTextDisplay($ordem_servico->observacao, '', '16px', '');




        $row1 = $this->form->addFields([$label2,$text1],[$label3,$text77]);
        $row1->layout = [' col-sm-3',' col-sm-3'];

        $row2 = $this->form->addFields([$label4,$text11],[$label6,$text4],[$label8,$text7],[$label10,$text6]);
        $row2->layout = [' col-sm-3',' col-sm-3',' col-sm-3',' col-sm-3'];

        $row3 = $this->form->addFields([$label13,$text2],[$label15,$text3]);
        $row3->layout = [' col-sm-6','col-sm-6'];

        $row4 = $this->form->addFields([$label17,$text5],[$label21,$text10]);
        $row4->layout = [' col-sm-8',' col-sm-4'];

        $row5 = $this->form->addFields([$label19,$text9]);
        $row5->layout = [' col-sm-12'];

        $this->ordem_servico_atendimento_ordem_servico_id_list = new TQuickGrid;
        $this->ordem_servico_atendimento_ordem_servico_id_list->disableHtmlConversion();
        $this->ordem_servico_atendimento_ordem_servico_id_list->style = 'width:100%';
        $this->ordem_servico_atendimento_ordem_servico_id_list->disableDefaultClick();

        $column_tecnico_nome = $this->ordem_servico_atendimento_ordem_servico_id_list->addQuickColumn("Técnico", 'tecnico->nome', 'left');
        $column_problema_nome = $this->ordem_servico_atendimento_ordem_servico_id_list->addQuickColumn("Problema", 'problema->nome', 'left');
        $column_causa_nome = $this->ordem_servico_atendimento_ordem_servico_id_list->addQuickColumn("Causa", 'causa->nome', 'left');
        $column_solucao_nome = $this->ordem_servico_atendimento_ordem_servico_id_list->addQuickColumn("Solução", 'solucao->nome', 'left');
        $column_data_atendimento_transformed = $this->ordem_servico_atendimento_ordem_servico_id_list->addQuickColumn("Data", 'data_atendimento', 'center');
        $column_created_at_transformed = $this->ordem_servico_atendimento_ordem_servico_id_list->addQuickColumn("Criado em", 'created_at', 'center');
        $column_horario_inicial = $this->ordem_servico_atendimento_ordem_servico_id_list->addQuickColumn("Horário Inicial", 'horario_inicial', 'center');
        $column_horario_final = $this->ordem_servico_atendimento_ordem_servico_id_list->addQuickColumn("Horário final", 'horario_final', 'center');
        $column_observacao = $this->ordem_servico_atendimento_ordem_servico_id_list->addQuickColumn("Obs", 'observacao', 'left');

        $column_data_atendimento_transformed->setTransformer(function($value, $object, $row, $cell = null, $last_row = null)
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
        });

        $column_created_at_transformed->setTransformer(function($value, $object, $row, $cell = null, $last_row = null)
        {
            if(!empty(trim($value)))
            {
                try
                {
                    $date = new DateTime($value);
                    return $date->format('d/m/Y H:i');
                }
                catch (Exception $e)
                {
                    return $value;
                }
            }
        });

        $this->ordem_servico_atendimento_ordem_servico_id_list->createModel();

        $criteria_ordem_servico_atendimento_ordem_servico_id = new TCriteria();
        $criteria_ordem_servico_atendimento_ordem_servico_id->add(new TFilter('ordem_servico_id', '=', $ordem_servico->id));

        $criteria_ordem_servico_atendimento_ordem_servico_id->setProperty('order', 'id desc');

        $ordem_servico_atendimento_ordem_servico_id_items = OrdemServicoAtendimento::getObjects($criteria_ordem_servico_atendimento_ordem_servico_id);

        $this->ordem_servico_atendimento_ordem_servico_id_list->addItems($ordem_servico_atendimento_ordem_servico_id_items);

        $icon = new TImage('fas:wrench #000000');
        $title = new TTextDisplay("{$icon} ATENDIMENTOS", '#333', '14px', '{$fontStyle}');

        $panel = new TPanelGroup($title, '#f5f5f5');
        $panel->class = 'panel panel-default formView-detail';
        $panel->add(new BootstrapDatagridWrapper($this->ordem_servico_atendimento_ordem_servico_id_list));

        $this->form->addContent([$panel]);

        $this->ordem_servico_item_ordem_servico_id_list = new TQuickGrid;
        $this->ordem_servico_item_ordem_servico_id_list->disableHtmlConversion();
        $this->ordem_servico_item_ordem_servico_id_list->style = 'width:100%';
        $this->ordem_servico_item_ordem_servico_id_list->disableDefaultClick();

        $column_produto_tipo_produto_nome = $this->ordem_servico_item_ordem_servico_id_list->addQuickColumn("Tipo", 'produto->tipo_produto->nome', 'left');
        $column_produto_nome = $this->ordem_servico_item_ordem_servico_id_list->addQuickColumn("Produto", 'produto->nome', 'left');
        $column_quantidade = $this->ordem_servico_item_ordem_servico_id_list->addQuickColumn("Quantidade", 'quantidade', 'center');
        $column_valor_transformed = $this->ordem_servico_item_ordem_servico_id_list->addQuickColumn("Valor", 'valor', 'left');
        $column_desconto_transformed = $this->ordem_servico_item_ordem_servico_id_list->addQuickColumn("Desconto", 'desconto', 'left');
        $column_valor_total_transformed = $this->ordem_servico_item_ordem_servico_id_list->addQuickColumn("Valor total", 'valor_total', 'left');

        $column_valor_total_transformed->setTotalFunction( function($values) { 
            return array_sum((array) $values); 
        }); 

        $column_valor_transformed->setTransformer(function($value, $object, $row, $cell = null, $last_row = null)
        {
            if(!$value)
            {
                $value = 0;
            }

            if(is_numeric($value))
            {
                return "R$ " . number_format($value, 2, ",", ".");
            }
            else
            {
                return $value;
            }
        });

        $column_desconto_transformed->setTransformer(function($value, $object, $row, $cell = null, $last_row = null)
        {
            if(!$value)
            {
                $value = 0;
            }

            if(is_numeric($value))
            {
                return "R$ " . number_format($value, 2, ",", ".");
            }
            else
            {
                return $value;
            }
        });

        $column_valor_total_transformed->setTransformer(function($value, $object, $row, $cell = null, $last_row = null)
        {
            if(!$value)
            {
                $value = 0;
            }

            if(is_numeric($value))
            {
                return "R$ " . number_format($value, 2, ",", ".");
            }
            else
            {
                return $value;
            }
        });

        $this->ordem_servico_item_ordem_servico_id_list->createModel();

        $criteria_ordem_servico_item_ordem_servico_id = new TCriteria();
        $criteria_ordem_servico_item_ordem_servico_id->add(new TFilter('ordem_servico_id', '=', $ordem_servico->id));

        $criteria_ordem_servico_item_ordem_servico_id->setProperty('order', 'id desc');

        $ordem_servico_item_ordem_servico_id_items = OrdemServicoItem::getObjects($criteria_ordem_servico_item_ordem_servico_id);

        $this->ordem_servico_item_ordem_servico_id_list->addItems($ordem_servico_item_ordem_servico_id_items);

        $icon = new TImage('fas:box-open #000000');
        $title = new TTextDisplay("{$icon} PRODUTOS/SERVIÇOS", '#333', '14px', '{$fontStyle}');

        $panel = new TPanelGroup($title, '#f5f5f5');
        $panel->class = 'panel panel-default formView-detail';
        $panel->add(new BootstrapDatagridWrapper($this->ordem_servico_item_ordem_servico_id_list));

        $this->form->addContent([$panel]);

        parent::setTargetContainer('adianti_right_panel');

        $btnClose = new TButton('closeCurtain');
        $btnClose->class = 'btn btn-sm btn-default';
        $btnClose->style = 'margin-right:10px;';
        $btnClose->onClick = "Template.closeRightPanel();";
        $btnClose->setLabel("Fechar");
        $btnClose->setImage('fas:times');

        $this->form->addHeaderWidget($btnClose);

        TTransaction::close();
        parent::add($this->form);

        $style = new TStyle('right-panel > .container-part[page-name=OrdemServicoFormViewPublico]');
        $style->width = '70% !important';   
        $style->show(true);

    }

    public function onShow($param = null)
    {     

    }

}

