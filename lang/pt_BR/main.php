<?php

declare(strict_types=1);

return [
    'menu' => [
        'sidebar_group_label' => 'Plataforma',
        'sidebar_items' => [
            'dashboard' => 'Painel',
            'medical_file' => 'Prontuário',
            'exams' => 'Exames',
            'exams_items' => [
                'complete_blood_count' => 'Hemograma Completo',
                'glucose' => 'Glicose',
                'lipid_profile' => 'Perfil Lipídico',
                'ultrasensitive_tsh' => 'TSH Ultra-sensível',
                'urea_and_creatinine' => 'Ureia e Creatinina',
                'vitamin_b12' => 'Vitamina B12',
                'vitamin_d3' => 'Vitamina D3',
            ],
            'footer_items' => [
                'repository' => 'Repositório',
                'made_by' => 'Feito por Barata Ribeiro',
            ],
        ],
        'user_dropdown' => [
            'settings' => 'Configurações',
            'logout' => 'Sair',
        ],
    ],
    'command_bar' => [
        'placeholder' => 'Pesquisar...',
        'main_navigation' => 'Navegação Principal',
        'search_placeholder' => 'Digite um comando ou pesquisa...',
        'no_results' => 'Nenhum resultado encontrado.',
        'settings_items' => [
            'profile' => 'Perfil',
            'change_password' => 'Alterar Senha',
            'appearance' => 'Aparência',
        ],
    ],
    'appearance_dropdown' => [
        'title' => 'Selecionar Aparência',
        'light' => 'Claro',
        'dark' => 'Escuro',
        'system' => 'Sistema',
    ],
    'settings_layout' => [
        'sidebar_items' => [
            'profile' => 'Perfil',
            'medical_file' => 'Prontuário',
            'security' => 'Segurança',
            'appearance' => 'Aparência',
        ],
        'title' => 'Configurações',
        'description' => 'Gerencie seu perfil e configurações de conta',
    ],
    'chart_empty_state' => [
        'title' => 'Sem Dados',
        'description' => 'Nenhum dado disponível para exibir o gráfico. Por favor, adicione alguns dados para visualizar seus insights.',
        'action_label' => 'Criar novo registro deste tipo',
        'action' => 'Criar',
    ],
    'data_table' => [
        'column_header' => [
            'asc' => 'Ascendente',
            'desc' => 'Descendente',
            'clear' => 'Limpar ordenação',
            'hide' => 'Ocultar coluna',
        ],
        'column_visibility' => [
            'action' => 'Visualizar',
            'label' => 'Alternar colunas',
        ],
        'toolbar' => [
            'search' => [
                'flash_error' => 'Falha ao realizar a pesquisa. Por favor, tente novamente.',
                'placeholder' => 'Digite para pesquisar...',
                'action' => 'Pesquisar',
            ],
            'faceted_filter' => [
                'badge_selected_suffix' => 'selecionado',
                'empty_results' => 'Nenhum resultado encontrado.',
                'clear_titled_action' => 'Limpar filtro {title}',
                'clear_action' => 'Limpar filtros',
            ],
            'eraser_label' => 'Limpar filtros',
        ],
        'create_record' => [
            'label' => 'Criar novo registro deste tipo',
            'action' => 'Criar',
        ],
        'export_record' => [
            'label' => 'Escolher como exportar os dados',
            'action' => 'Exportar',
            'dropdown_label' => 'Exportáveis',
            'csv_label' => 'Exportar como CSV',
            'pdf_label' => 'Exportar como PDF',
        ],
        'empty_message' => 'Nenhum registro encontrado.',
        'pagination' => [
            'info' => 'Mostrando {from} a {to} de {total} resultados',
            'per_page_label' => 'Linhas por página',
            'first_page_label' => 'Ir para a primeira página',
            'previous_page_label' => 'Ir para a página anterior',
            'next_page_label' => 'Ir para a próxima página',
            'last_page_label' => 'Ir para a última página',
        ],
    ],
];
