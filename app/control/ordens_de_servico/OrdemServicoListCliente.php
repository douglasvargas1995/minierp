<?php

class OrdemServicoListCliente extends TPage
{
    private $form; // form
    private $datagrid; // listing
    private $pageNavigation;
    private $loaded;
    private $filter_criteria;
    private static $database = 'minierp';
    private static $activeRecord = 'OrdemServico';
    private static $primaryKey = 'id';
    private static $formName = 'form_OrdemServicoList';
    private $showMethods = ['onReload', 'onSearch', 'onRefresh', 'onClearFilters'];
    private $limit = 20;

    /**
     * Class constructor
     * Creates the page, the form and the listing
     */
    public function __construct($param = null)
    {
        parent::__construct();

        if(!empty($param['target_container']))
        {
            $this->adianti_target_container = $param['target_container'];
        }

        // creates the form
        $this->form = new BootstrapFormBuilder(self::$formName);

        // define the form title
        $this->form->setFormTitle("Listagem de ordens de serviço");
        $this->limit = 10;

        $criteria_cliente_id = new TCriteria();

        $filterVar = GrupoPessoa::CLIENTE;
        $criteria_cliente_id->add(new TFilter('id', 'in', "(SELECT pessoa_id FROM pessoa_grupo WHERE grupo_pessoa_id = '{$filterVar}')")); 

        $id = new TEntry('id');
        $cliente_id = new TDBCombo('cliente_id', 'minierp', 'Pessoa', 'id', '{nome}','nome asc' , $criteria_cliente_id );
        $descricao = new TEntry('descricao');
        $data_inicio = new TDate('data_inicio');
        $data_inicio_fim = new TDate('data_inicio_fim');
        $data_fim = new TDate('data_fim');
        $data_fim_fim = new TDate('data_fim_fim');
        $data_prevista = new TDate('data_prevista');
        $data_prevista_fim = new TDate('data_prevista_fim');


        $cliente_id->enableSearch();
        $data_fim->setMask('dd/mm/yyyy');
        $data_inicio->setMask('dd/mm/yyyy');
        $data_fim_fim->setMask('dd/mm/yyyy');
        $data_prevista->setMask('dd/mm/yyyy');
        $data_inicio_fim->setMask('dd/mm/yyyy');
        $data_prevista_fim->setMask('dd/mm/yyyy');

        $data_fim->setDatabaseMask('yyyy-mm-dd');
        $data_inicio->setDatabaseMask('yyyy-mm-dd');
        $data_fim_fim->setDatabaseMask('yyyy-mm-dd');
        $data_prevista->setDatabaseMask('yyyy-mm-dd');
        $data_inicio_fim->setDatabaseMask('yyyy-mm-dd');
        $data_prevista_fim->setDatabaseMask('yyyy-mm-dd');

        $id->setSize(160);
        $data_fim->setSize(110);
        $data_inicio->setSize(110);
        $descricao->setSize('100%');
        $data_fim_fim->setSize(110);
        $cliente_id->setSize('100%');
        $data_prevista->setSize(110);
        $data_inicio_fim->setSize(110);
        $data_prevista_fim->setSize(110);

        $row1 = $this->form->addFields([new TLabel("Id:", null, '14px', null, '100%'),$id],[new TLabel("Cliente:", null, '14px', null, '100%'),$cliente_id]);
        $row1->layout = ['col-sm-6','col-sm-6'];

        $row2 = $this->form->addFields([new TLabel("Descricao:", null, '14px', null, '100%'),$descricao]);
        $row2->layout = [' col-sm-12'];

        $row3 = $this->form->addFields([new TLabel("Data início:", null, '14px', null, '100%'),$data_inicio,new TLabel("até:", null, '14px', null),$data_inicio_fim],[new TLabel("Data fim:", null, '14px', null, '100%'),$data_fim,new TLabel("até:", null, '14px', null),$data_fim_fim],[new TLabel("Data prevista:", null, '14px', null, '100%'),$data_prevista,new TLabel("até:", null, '14px', null),$data_prevista_fim]);
        $row3->layout = [' col-sm-4',' col-sm-4',' col-sm-4'];

        // keep the form filled during navigation with session data
        $this->form->setData( TSession::getValue(__CLASS__.'_filter_data') );

        $btn_onsearch = $this->form->addAction("Buscar", new TAction([$this, 'onSearch']), 'fas:search #ffffff');
        $this->btn_onsearch = $btn_onsearch;
        $btn_onsearch->addStyleClass('btn-primary'); 

        // creates a Datagrid
        $this->datagrid = new TDataGrid;
        $this->datagrid->disableHtmlConversion();

        $this->datagrid_form = new TForm('datagrid_'.self::$formName);
        $this->datagrid_form->onsubmit = 'return false';

        $this->datagrid = new BootstrapDatagridWrapper($this->datagrid);
        $this->filter_criteria = new TCriteria;

        if(!empty($param['id_cliente']))
        {
            TSession::setValue(__CLASS__.'load_filter_id', $param['id_cliente']);
        }
        $filterVar = TSession::getValue(__CLASS__.'load_filter_id');

        $this->filter_criteria->add(new TFilter('id', 'in', "(SELECT ordem_servico_id FROM conta WHERE ordem_servico_id in  (SELECT id FROM ordem_servico WHERE cliente_id = '{$filterVar}') )"));
        $filterVar = "FINALIZADO";
        $this->filter_criteria->add(new TFilter('situacao', '=', $filterVar));

        $this->datagrid->style = 'width: 100%';
        $this->datagrid->setHeight(250);

        $column_id = new TDataGridColumn('id', "Id", 'center' , '70px');
        $column_cliente_nome = new TDataGridColumn('cliente->nome', "Cliente", 'left');
        $column_data_inicio_transformed = new TDataGridColumn('data_inicio', "Data início", 'left');
        $column_data_fim_transformed = new TDataGridColumn('data_fim', "Data fim", 'left');
        $column_data_prevista_transformed = new TDataGridColumn('data_prevista', "Data prevista", 'left');
        $column_created_at_transformed = new TDataGridColumn('created_at', "Criado em", 'left');
        $column_valor_total_transformed = new TDataGridColumn('valor_total', "Valor total", 'left');
        $column_status = new TDataGridColumn('status', "Status", 'center');

        $column_data_inicio_transformed->setTransformer(function($value, $object, $row, $cell = null, $last_row = null)
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

        $column_data_fim_transformed->setTransformer(function($value, $object, $row, $cell = null, $last_row = null)
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

        $column_data_prevista_transformed->setTransformer(function($value, $object, $row, $cell = null, $last_row = null)
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

        $order_id = new TAction(array($this, 'onReload'));
        $order_id->setParameter('order', 'id');
        $column_id->setAction($order_id);

        $this->datagrid->addColumn($column_id);
        $this->datagrid->addColumn($column_cliente_nome);
        $this->datagrid->addColumn($column_data_inicio_transformed);
        $this->datagrid->addColumn($column_data_fim_transformed);
        $this->datagrid->addColumn($column_data_prevista_transformed);
        $this->datagrid->addColumn($column_created_at_transformed);
        $this->datagrid->addColumn($column_valor_total_transformed);
        $this->datagrid->addColumn($column_status);

        $action_onShow = new TDataGridAction(array('OrdemServicoFormView', 'onShow'));
        $action_onShow->setUseButton(false);
        $action_onShow->setButtonClass('btn btn-default btn-sm');
        $action_onShow->setLabel("Visualizar");
        $action_onShow->setImage('fas:search-plus #9C27B0');
        $action_onShow->setField(self::$primaryKey);

        $this->datagrid->addAction($action_onShow);

        $action_onGenerate = new TDataGridAction(array('OrdemServicoDocument', 'onGenerate'));
        $action_onGenerate->setUseButton(false);
        $action_onGenerate->setButtonClass('btn btn-default btn-sm');
        $action_onGenerate->setLabel("Documento OS");
        $action_onGenerate->setImage('far:file-pdf #FF5722');
        $action_onGenerate->setField(self::$primaryKey);

        $this->datagrid->addAction($action_onGenerate);

        // create the datagrid model
        $this->datagrid->createModel();

        // creates the page navigation
        $this->pageNavigation = new TPageNavigation;
        $this->pageNavigation->enableCounters();
        $this->pageNavigation->setAction(new TAction(array($this, 'onReload')));
        $this->pageNavigation->setWidth($this->datagrid->getWidth());

        $panel = new TPanelGroup("Listagem de ordens de serviço");
        $panel->datagrid = 'datagrid-container';
        $this->datagridPanel = $panel;
        $this->datagrid_form->add($this->datagrid);
        $panel->add($this->datagrid_form);

        $panel->getBody()->class .= ' table-responsive';

        $panel->addFooter($this->pageNavigation);

        $headerActions = new TElement('div');
        $headerActions->class = ' datagrid-header-actions ';
        $headerActions->style = 'justify-content: space-between;';

        $head_left_actions = new TElement('div');
        $head_left_actions->class = ' datagrid-header-actions-left-actions ';

        $head_right_actions = new TElement('div');
        $head_right_actions->class = ' datagrid-header-actions-left-actions ';

        $headerActions->add($head_left_actions);
        $headerActions->add($head_right_actions);

        $panel->getBody()->insert(0, $headerActions);

        $btnShowCurtainFilters = new TButton('button_btnShowCurtainFilters');
        $btnShowCurtainFilters->setAction(new TAction(['OrdemServicoListCliente', 'onShowCurtainFilters']), "Filtros");
        $btnShowCurtainFilters->addStyleClass('btn-default');
        $btnShowCurtainFilters->setImage('fas:filter #000000');

        $this->datagrid_form->addField($btnShowCurtainFilters);

        $button_limpar_filtros = new TButton('button_button_limpar_filtros');
        $button_limpar_filtros->setAction(new TAction(['OrdemServicoListCliente', 'onClearFilters']), "Limpar filtros");
        $button_limpar_filtros->addStyleClass('btn-default');
        $button_limpar_filtros->setImage('fas:eraser #f44336');

        $this->datagrid_form->addField($button_limpar_filtros);

        $button_atualizar = new TButton('button_button_atualizar');
        $button_atualizar->setAction(new TAction(['OrdemServicoListCliente', 'onRefresh']), "Atualizar");
        $button_atualizar->addStyleClass('btn-default');
        $button_atualizar->setImage('fas:sync-alt #03a9f4');

        $this->datagrid_form->addField($button_atualizar);

        $head_left_actions->add($btnShowCurtainFilters);
        $head_left_actions->add($button_limpar_filtros);
        $head_left_actions->add($button_atualizar);

        $this->btnShowCurtainFilters = $btnShowCurtainFilters;

        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        if(empty($param['target_container']))
        {
            $container->add(TBreadCrumb::create(["Ordens de Serviço","Ordens de serviço do Cliente"]));
        }

        $container->add($panel);

        parent::add($container);

    }

    public static function onShowCurtainFilters($param = null) 
    {
        try 
        {
            //code here

                        $filter = new self([]);

            $btnClose = new TButton('closeCurtain');
            $btnClose->class = 'btn btn-sm btn-default';
            $btnClose->style = 'margin-right:10px;';
            $btnClose->onClick = "Template.closeRightPanel();";
            $btnClose->setLabel("Fechar");
            $btnClose->setImage('fas:times');

            $filter->form->addHeaderWidget($btnClose);

            $page = new TPage();
            $page->setTargetContainer('adianti_right_panel');
            $page->setProperty('page-name', 'OrdemServicoListClienteSearch');
            $page->setProperty('page_name', 'OrdemServicoListClienteSearch');
            $page->adianti_target_container = 'adianti_right_panel';
            $page->target_container = 'adianti_right_panel';
            $page->add($filter->form);
            $page->setIsWrapped(true);
            $page->show();

            $style = new TStyle('right-panel > .container-part[page-name=OrdemServicoListClienteSearch]');
            $style->width = '80% !important';
            $style->show(true);

            //</autoCode>
        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());    
        }
    }
    public function onClearFilters($param = null) 
    {
        TSession::setValue(__CLASS__.'_filter_data', NULL);
        TSession::setValue(__CLASS__.'_filters', NULL);

        $this->onReload(['offset' => 0, 'first_page' => 1]);
    }
    public function onRefresh($param = null) 
    {
        $this->onReload([]);
    }

    /**
     * Register the filter in the session
     */
    public function onSearch($param = null)
    {
        $data = $this->form->getData();
        $filters = [];

        TSession::setValue(__CLASS__.'_filter_data', NULL);
        TSession::setValue(__CLASS__.'_filters', NULL);

        if (isset($data->id) AND ( (is_scalar($data->id) AND $data->id !== '') OR (is_array($data->id) AND (!empty($data->id)) )) )
        {

            $filters[] = new TFilter('id', '=', $data->id);// create the filter 
        }

        if (isset($data->cliente_id) AND ( (is_scalar($data->cliente_id) AND $data->cliente_id !== '') OR (is_array($data->cliente_id) AND (!empty($data->cliente_id)) )) )
        {

            $filters[] = new TFilter('cliente_id', '=', $data->cliente_id);// create the filter 
        }

        if (isset($data->descricao) AND ( (is_scalar($data->descricao) AND $data->descricao !== '') OR (is_array($data->descricao) AND (!empty($data->descricao)) )) )
        {

            $filters[] = new TFilter('descricao', 'like', "%{$data->descricao}%");// create the filter 
        }

        if (isset($data->data_inicio) AND ( (is_scalar($data->data_inicio) AND $data->data_inicio !== '') OR (is_array($data->data_inicio) AND (!empty($data->data_inicio)) )) )
        {

            $filters[] = new TFilter('data_inicio', '>=', $data->data_inicio);// create the filter 
        }

        if (isset($data->data_inicio_fim) AND ( (is_scalar($data->data_inicio_fim) AND $data->data_inicio_fim !== '') OR (is_array($data->data_inicio_fim) AND (!empty($data->data_inicio_fim)) )) )
        {

            $filters[] = new TFilter('data_inicio', '<=', $data->data_inicio_fim);// create the filter 
        }

        if (isset($data->data_fim) AND ( (is_scalar($data->data_fim) AND $data->data_fim !== '') OR (is_array($data->data_fim) AND (!empty($data->data_fim)) )) )
        {

            $filters[] = new TFilter('data_fim', '>=', $data->data_fim);// create the filter 
        }

        if (isset($data->data_fim_fim) AND ( (is_scalar($data->data_fim_fim) AND $data->data_fim_fim !== '') OR (is_array($data->data_fim_fim) AND (!empty($data->data_fim_fim)) )) )
        {

            $filters[] = new TFilter('data_fim', '<=', $data->data_fim_fim);// create the filter 
        }

        if (isset($data->data_prevista) AND ( (is_scalar($data->data_prevista) AND $data->data_prevista !== '') OR (is_array($data->data_prevista) AND (!empty($data->data_prevista)) )) )
        {

            $filters[] = new TFilter('data_prevista', '>=', $data->data_prevista);// create the filter 
        }

        if (isset($data->data_prevista_fim) AND ( (is_scalar($data->data_prevista_fim) AND $data->data_prevista_fim !== '') OR (is_array($data->data_prevista_fim) AND (!empty($data->data_prevista_fim)) )) )
        {

            $filters[] = new TFilter('data_prevista', '<=', $data->data_prevista_fim);// create the filter 
        }

        // fill the form with data again
        $this->form->setData($data);

        // keep the search data in the session
        TSession::setValue(__CLASS__.'_filter_data', $data);
        TSession::setValue(__CLASS__.'_filters', $filters);

        $this->onReload(['offset' => 0, 'first_page' => 1]);
    }

    /**
     * Load the datagrid with data
     */
    public function onReload($param = NULL)
    {
        try
        {
            // open a transaction with database 'minierp'
            TTransaction::open(self::$database);

            // creates a repository for OrdemServico
            $repository = new TRepository(self::$activeRecord);

            $criteria = clone $this->filter_criteria;

            if (empty($param['order']))
            {
                $param['order'] = 'id';    
            }

            if (empty($param['direction']))
            {
                $param['direction'] = 'desc';
            }

            $criteria->setProperties($param); // order, offset
            $criteria->setProperty('limit', $this->limit);

            if($filters = TSession::getValue(__CLASS__.'_filters'))
            {
                foreach ($filters as $filter) 
                {
                    $criteria->add($filter);       
                }
            }

            //</blockLine><btnShowCurtainFiltersAutoCode>
            if(!empty($this->btnShowCurtainFilters) && empty($this->btnShowCurtainFiltersAdjusted))
            {
                $this->btnShowCurtainFiltersAdjusted = true;
                $this->btnShowCurtainFilters->style = 'position: relative';
                $countFilters = count($filters ?? []);
                $this->btnShowCurtainFilters->setLabel($this->btnShowCurtainFilters->getLabel(). "<span class='badge badge-success' style='position: absolute'>{$countFilters}<span>");
            }
            //</blockLine></btnShowCurtainFiltersAutoCode>

            // load the objects according to criteria
            $objects = $repository->load($criteria, FALSE);

            $this->datagrid->clear();
            if ($objects)
            {
                // iterate the collection of active records
                foreach ($objects as $object)
                {

                    $row = $this->datagrid->addItem($object);
                    $row->id = "row_{$object->id}";

                }
            }

            // reset the criteria for record count
            $criteria->resetProperties();
            $count= $repository->count($criteria);

            $this->pageNavigation->setCount($count); // count of records
            $this->pageNavigation->setProperties($param); // order, page
            $this->pageNavigation->setLimit($this->limit); // limit

            // close the transaction
            TTransaction::close();
            $this->loaded = true;

            return $objects;
        }
        catch (Exception $e) // in case of exception
        {
            // shows the exception error message
            new TMessage('error', $e->getMessage());
            // undo all pending operations
            TTransaction::rollback();
        }
    }

    public function onShow($param = null)
    {

    }

    /**
     * method show()
     * Shows the page
     */
    public function show()
    {
        // check if the datagrid is already loaded
        if (!$this->loaded AND (!isset($_GET['method']) OR !(in_array($_GET['method'],  $this->showMethods))) )
        {
            if (func_num_args() > 0)
            {
                $this->onReload( func_get_arg(0) );
            }
            else
            {
                $this->onReload();
            }
        }
        parent::show();
    }

}

