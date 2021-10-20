<?php
    trait AttributeCalculationTrait {
        private $ats = array();
        private $at  = array();
        private $atl = array(
            'less_conv' => 0
        );

        public $_calc_at = array(
            'atk_fisico_item' => 0,
            'atk_magico_item' => 0,
            'def_fisico_item' => 0,
            'def_magico_item' => 0,
            'def_base_item'   => 0,
            'atk_fisico_arv'  => 0,
            'atk_magico_arv'  => 0,
            'def_fisico_arv'  => 0,
            'def_magico_arv'  => 0,
            'def_base_arv'    => 0,
            'ryou_arv'        => 0,
            'exp_arv'         => 0
        );

        public $_calc_atp = array(
            'atk_fisico_item' => 0,
            'atk_magico_item' => 0,
            'def_fisico_item' => 0,
            'def_magico_item' => 0,
            'def_base_item'   => 0,
            'atk_fisico_arv'  => 0,
            'atk_magico_arv'  => 0,
            'def_fisico_arv'  => 0,
            'def_magico_arv'  => 0,
            'def_base_arv'    => 0,
            'atk_fisico_mod'  => 0,
            'atk_magico_mod'  => 0,
            'def_fisico_mod'  => 0,
            'def_magico_mod'  => 0,
            'def_base_mod'    => 0,
            'ryou_arv'        => 0,
            'exp_arv'         => 0
        );

        function atCalc() {
            // Calculos de HP, SP e STA --->
            // Define os ats calculados --->
            // Atributos base, sem buff1s --->
            $this->setLocalAttribute('tai_calc', $this->getAttribute('tai_raw') + $this->getAttribute('tai_item') + $this->getAttribute('tai_arv'));
            $this->setLocalAttribute('ken_calc', $this->getAttribute('ken_raw') + $this->getAttribute('ken_item') + $this->getAttribute('ken_arv'));
            $this->setLocalAttribute('nin_calc', $this->getAttribute('nin_raw') + $this->getAttribute('nin_item') + $this->getAttribute('nin_arv'));
            $this->setLocalAttribute('gen_calc', $this->getAttribute('gen_raw') + $this->getAttribute('gen_item') + $this->getAttribute('gen_arv'));
            $this->setLocalAttribute('agi_calc', $this->getAttribute('agi_raw') + $this->getAttribute('agi_item') + $this->getAttribute('agi_arv'));
            $this->setLocalAttribute('for_calc', $this->getAttribute('for_raw') + $this->getAttribute('for_item') + $this->getAttribute('for_arv'));
            $this->setLocalAttribute('con_calc', $this->getAttribute('con_raw') + $this->getAttribute('con_item') + $this->getAttribute('con_arv'));
            $this->setLocalAttribute('int_calc', $this->getAttribute('int_raw') + $this->getAttribute('int_item') + $this->getAttribute('int_arv'));
            $this->setLocalAttribute('ene_calc', $this->getAttribute('ene_raw2') + $this->getAttribute('ene_item') + $this->getAttribute('ene_arv'));
            $this->setLocalAttribute('ene_calc2', $this->getAttribute('ene_raw') - $this->getAttribute('ene_raw2'));
            $this->setLocalAttribute('res_calc', $this->getAttribute('res_raw') + $this->getAttribute('res_item') + $this->getAttribute('res_arv'));

            // <---

            // Atributos com buffs --->
            $this->setLocalAttribute('tai_calc_mod', $this->getAttribute('tai_calc') + $this->getAttribute('tai_mod'));
            $this->setLocalAttribute('ken_calc_mod', $this->getAttribute('ken_calc') + $this->getAttribute('ken_mod'));
            $this->setLocalAttribute('nin_calc_mod', $this->getAttribute('nin_calc') + $this->getAttribute('nin_mod'));
            $this->setLocalAttribute('gen_calc_mod', $this->getAttribute('gen_calc') + $this->getAttribute('gen_mod'));
            $this->setLocalAttribute('agi_calc_mod', $this->getAttribute('agi_calc') + $this->getAttribute('agi_mod'));
            $this->setLocalAttribute('for_calc_mod', $this->getAttribute('for_calc') + $this->getAttribute('for_mod'));
            $this->setLocalAttribute('con_calc_mod', $this->getAttribute('con_calc') + $this->getAttribute('con_mod'));
            $this->setLocalAttribute('int_calc_mod', $this->getAttribute('int_calc') + $this->getAttribute('int_mod'));
            $this->setLocalAttribute('ene_calc_mod', $this->getAttribute('ene_calc') + $this->getAttribute('ene_mod'));
            $this->setLocalAttribute('ene_calc_mod2', $this->getAttribute('ene_calc2'));
            $this->setLocalAttribute('res_calc_mod', $this->getAttribute('res_calc') + $this->getAttribute('res_mod'));
            // <---

            //Nova regra de atributo
            $this->setLocalAttribute('def_base_calc',   0);

            switch ($this->getAttribute('id_classe_tipo')) {
                case 1:

                    //Formulas Velhas
                    $this->setLocalAttribute('atk_fisico_calc', round($this->getAttribute('for_calc_mod') / 2));
                    $this->setLocalAttribute('atk_magico_calc', round($this->getAttribute('int_calc_mod') / 2));
                    $this->setLocalAttribute('def_fisico_calc', floor($this->getAttribute('res_calc_mod') / 2));
                    $this->setLocalAttribute('def_magico_calc', floor($this->getAttribute('res_calc_mod')  / 2));
                    break;

                case 2:

                    //Formulas Velhas
                    $this->setLocalAttribute('atk_fisico_calc', round($this->getAttribute('for_calc_mod') / 2));
                    $this->setLocalAttribute('atk_magico_calc', round($this->getAttribute('int_calc_mod') / 2));
                    $this->setLocalAttribute('def_fisico_calc', floor($this->getAttribute('res_calc_mod') / 2));
                    $this->setLocalAttribute('def_magico_calc', floor($this->getAttribute('res_calc_mod')  / 2));

                    break;
                case 3:
                    //Formulas Velhas
                    $this->setLocalAttribute('atk_fisico_calc', round($this->getAttribute('for_calc_mod') / 2));
                    $this->setLocalAttribute('atk_magico_calc', round($this->getAttribute('int_calc_mod') / 2));
                    $this->setLocalAttribute('def_fisico_calc', floor($this->getAttribute('res_calc_mod') / 2));
                    $this->setLocalAttribute('def_magico_calc', floor($this->getAttribute('res_calc_mod')  / 2));
                    break;
                case 4:
                    //Formulas Velhas
                    $this->setLocalAttribute('atk_fisico_calc', round($this->getAttribute('for_calc_mod') / 2));
                    $this->setLocalAttribute('atk_magico_calc', round($this->getAttribute('int_calc_mod') / 2));
                    $this->setLocalAttribute('def_fisico_calc', floor($this->getAttribute('res_calc_mod') / 2));
                    $this->setLocalAttribute('def_magico_calc', floor($this->getAttribute('res_calc_mod')  / 2));
                    break;
            }

            // Aplica os valores para ataque e defesa --->
            if (isset($this->_calc_at) && isset($this->_calc_atp)) {
                // Porcentagens de item
                $this->setLocalAttribute('atk_fisico_item',    round($this->_calc_at['atk_fisico_item']    + percent($this->_calc_atp['atk_fisico_item'],    $this->getAttribute('atk_fisico_calc'))));
                $this->setLocalAttribute('atk_magico_item',    round($this->_calc_at['atk_magico_item']    + percent($this->_calc_atp['atk_magico_item'],    $this->getAttribute('atk_magico_calc'))));
                $this->setLocalAttribute('def_fisico_item',    round($this->_calc_at['def_fisico_item']    + percent($this->_calc_atp['def_fisico_item'],    $this->getAttribute('def_fisico_calc'))));
                $this->setLocalAttribute('def_magico_item',    round($this->_calc_at['def_magico_item']    + percent($this->_calc_atp['def_magico_item'],    $this->getAttribute('def_magico_calc'))));
                $this->setLocalAttribute('def_base_item',        round($this->_calc_at['def_base_item']    + percent($this->_calc_atp['def_base_item'],        $this->getAttribute('def_base_calc'))));

                // Porcentagens da arvore
                $this->setLocalAttribute('atk_fisico_arv',    round($this->_calc_at['atk_fisico_arv']    + percent($this->_calc_atp['atk_fisico_arv'],    $this->getAttribute('atk_fisico_calc'))));
                $this->setLocalAttribute('atk_magico_arv',    round($this->_calc_at['atk_magico_arv']    + percent($this->_calc_atp['atk_magico_arv'],    $this->getAttribute('atk_magico_calc'))));
                $this->setLocalAttribute('def_fisico_arv',    round($this->_calc_at['def_fisico_arv']    + percent($this->_calc_atp['def_fisico_arv'],    $this->getAttribute('def_fisico_calc'))));
                $this->setLocalAttribute('def_magico_arv',    round($this->_calc_at['def_magico_arv']    + percent($this->_calc_atp['def_magico_arv'],    $this->getAttribute('def_magico_calc'))));
                $this->setLocalAttribute('def_base_arv',        round($this->_calc_at['def_base_arv']        + percent($this->_calc_atp['def_base_arv'],    $this->getAttribute('def_base_calc'))));
                // <---

                $atk_fisico_mod    = $this->getAttribute('atk_fisico_mod')    + percent($this->_calc_atp['atk_fisico_mod'],    $this->getAttribute('atk_fisico_calc'));
                $atk_magico_mod    = $this->getAttribute('atk_magico_mod')    + percent($this->_calc_atp['atk_magico_mod'],    $this->getAttribute('atk_magico_calc'));
                $def_fisico_mod    = $this->getAttribute('def_fisico_mod')    + percent($this->_calc_atp['def_fisico_mod'],    $this->getAttribute('def_fisico_calc'));
                $def_magico_mod    = $this->getAttribute('def_magico_mod')    + percent($this->_calc_atp['def_magico_mod'],    $this->getAttribute('def_magico_calc'));
                $def_base_mod    = $this->getAttribute('def_base_mod')        + percent($this->_calc_atp['def_base_mod'],    $this->getAttribute('def_base_calc'));

                // Aplica itens + arvore + mod
                $this->setLocalAttribute('def_base_calc',        $def_base_mod    + $this->getAttribute('def_base_calc')    + $this->getAttribute('def_base_item')    + $this->getAttribute('def_base_arv'));

                $this->setLocalAttribute('atk_fisico_calc',    $atk_fisico_mod    + $this->getAttribute('atk_fisico_calc')    + $this->getAttribute('atk_fisico_item')    + $this->getAttribute('atk_fisico_arv'));
                $this->setLocalAttribute('atk_magico_calc',    $atk_magico_mod    + $this->getAttribute('atk_magico_calc')    + $this->getAttribute('atk_magico_item')    + $this->getAttribute('atk_magico_arv'));
                $this->setLocalAttribute('def_fisico_calc',    $def_fisico_mod    + $this->getAttribute('def_fisico_calc')    + $this->getAttribute('def_fisico_item')    + $this->getAttribute('def_fisico_arv') + $this->getAttribute('def_base_calc'));
                $this->setLocalAttribute('def_magico_calc',    $def_magico_mod    + $this->getAttribute('def_magico_calc')    + $this->getAttribute('def_magico_item')    + $this->getAttribute('def_magico_arv') + $this->getAttribute('def_base_calc'));
            }
            // <---

            // Precisão --->
            $this->setLocalAttribute('prec_fisico_calc',  $this->getAttribute('prec_fisico_item') + $this->getAttribute('prec_fisico_arv') + $this->getAttribute('prec_fisico_mod'));
            $this->setLocalAttribute('prec_magico_calc', $this->getAttribute('prec_fisico_calc') + $this->getAttribute('con_calc_mod') + $this->getAttribute('prec_magico_item') + $this->getAttribute('prec_magico_arv') + $this->getAttribute('prec_magico_mod'));
            // <---

            // Criticos --->
            $cmin    = 0;
            $cmax    = 0;

            $cmin    += $this->getAttribute('crit_min_item') + $this->getAttribute('crit_min_arv') + $this->getAttribute('crit_min_mod');
            $cmax    += $this->getAttribute('crit_max_item') + $this->getAttribute('crit_max_arv') + $this->getAttribute('crit_max_mod');

            $ctotal    = round(10 + $this->getAttribute('level') / 2, 2);
            $ctotal    += $this->getAttribute('crit_total_item') + $this->getAttribute('crit_total_arv') + $this->getAttribute('crit_total_mod');
            // <---

            // Esquivas --->
            $emin    = 0;
            $emax    = 0;

            $emin    += $this->getAttribute('esq_min_item') + $this->getAttribute('esq_min_arv') + $this->getAttribute('esq_min_mod');
            $emax    += $this->getAttribute('esq_max_item') + $this->getAttribute('esq_max_arv') + $this->getAttribute('esq_max_mod');

            $etotal    = round(5 + $this->getAttribute('level') / 4, 2);
            $etotal    += $this->getAttribute('esq_total_item') + $this->getAttribute('esq_total_arv') + $this->getAttribute('esq_total_mod');
            // <---

            // Formulas Antigas
            // Adicionar alguma formula aqui se quiser que esses valores venham com valores iniciais
            $esq    = 0;
            $det    = 0;
            $conv    = 0;
            $conc    = 0;
            $esquiva = 0;


            $esq    += $this->getAttribute('esq_item')  + $this->getAttribute('esq_arv')  + $this->getAttribute('esq_mod') - percentf(85, $this->getAttribute('less_conv'));
            $det    += $this->getAttribute('level') + $this->getAttribute('det_item')  + $this->getAttribute('det_arv')  + $this->getAttribute('det_mod');
            $conv    += $this->getAttribute('conv_item') + $this->getAttribute('conv_arv') + $this->getAttribute('conv_mod');
            $esquiva += round($this->getAttribute('agi_calc_mod') / 6 + $this->getAttribute('esquiva_item') + $this->getAttribute('esquiva_arv') + $this->getAttribute('esquiva_mod'), 2);
            $conc    += $this->getAttribute('conc_item') + $this->getAttribute('conc_arv') + $this->getAttribute('conc_mod') - percentf(85, $this->getAttribute('less_conv'));

            // Ajuste maxima e mininmo
            foreach (array('esq', 'det', 'conv', 'esquiva', 'conc', 'ctotal', 'etotal') as $var) {
                //foreach(array('esq', 'det', 'conv', 'conc', 'cmin', 'cmax', 'emin','emax') as $var) {
                $$var    = $$var > 100 ? 100 : $$var;
                $$var    = $$var > 100 ? 100 : $$var;

                $$var    = $$var < 0 ? 0 : $$var;
                $$var    = $$var < 0 ? 0 : $$var;
            }
            // <---

            $this->setLocalAttribute('crit_min_calc', $cmin + $this->getAttribute('crit_min_raw'));
            $this->setLocalAttribute('crit_max_calc', $cmax + $this->getAttribute('crit_max_raw'));
            $this->setLocalAttribute('crit_total_calc', $ctotal + $this->getAttribute('crit_total_raw'));


            $this->setLocalAttribute('esq_min_calc', $emin + $this->getAttribute('esq_min_raw'));
            $this->setLocalAttribute('esq_max_calc', $emax + $this->getAttribute('esq_max_raw'));
            $this->setLocalAttribute('esq_total_calc', $etotal + $this->getAttribute('esq_total_raw'));

            $this->setLocalAttribute('conc_calc', $conc + $this->getAttribute('conc_raw'));
            $this->setLocalAttribute('esq_calc',  $esq + $this->getAttribute('esq_raw'));
            $this->setLocalAttribute('conv_calc', $conv + $this->getAttribute('conv_raw'));
            $this->setLocalAttribute('esquiva_calc', $esquiva + $this->getAttribute('esquiva_raw'));
            $this->setLocalAttribute('det_calc',  $det + $this->getAttribute('det_raw'));

            $this->setLocalAttribute('conc_calc2', $this->getAttribute('conc_raw2'));
            $this->setLocalAttribute('esq_calc2',  $this->getAttribute('esq_raw2'));
            $this->setLocalAttribute('conv_calc2', $this->getAttribute('conv_raw2'));
            $this->setLocalAttribute('esquiva_calc2', $this->getAttribute('esquiva_raw2'));
            $this->setLocalAttribute('det_calc2',  $this->getAttribute('det_raw2'));
            // <---
            // <---

            // Fórumlas --->
            $hp    = $this->getAttribute('ene_calc_mod') * 6;

            //Adicionado para Dividir o HP do treino e da Classe
            $hp    += $this->getAttribute('ene_calc_mod2') * 2;

            switch ($this->getAttribute('id_classe_tipo')) {
                case 1:
                    $sp        = $this->getAttribute('ene_calc_mod') * 6  + ($this->getAttribute('nin_calc_mod') + $this->getAttribute('gen_calc_mod')) * 7;
                    $sta    = $this->getAttribute('ene_calc_mod') * 6  + ($this->getAttribute('tai_calc_mod')) * 14 + ($this->getAttribute('ken_calc_mod')) * 7;
                    break;
                case 2:
                    $sp        = $this->getAttribute('ene_calc_mod') * 6  + ($this->getAttribute('nin_calc_mod')) * 14 + ($this->getAttribute('gen_calc_mod')) * 7;
                    $sta    = $this->getAttribute('ene_calc_mod') * 6  + ($this->getAttribute('tai_calc_mod') + $this->getAttribute('ken_calc_mod')) * 7;
                    break;
                case 3:
                    $sp        = $this->getAttribute('ene_calc_mod') * 6  + ($this->getAttribute('gen_calc_mod')) * 14 + ($this->getAttribute('nin_calc_mod')) * 7;
                    $sta    = $this->getAttribute('ene_calc_mod') * 6  + ($this->getAttribute('tai_calc_mod') + $this->getAttribute('ken_calc_mod')) * 7;
                    break;
                case 4:
                    $sp        = $this->getAttribute('ene_calc_mod') * 6  + ($this->getAttribute('nin_calc_mod') + $this->getAttribute('gen_calc_mod')) * 7;
                    $sta    = $this->getAttribute('ene_calc_mod') * 6  + ($this->getAttribute('ken_calc_mod')) * 14 + ($this->getAttribute('tai_calc_mod')) * 7;
                    break;
            }

            //Adicionado para Dividir o HP do treino e da Classe
            $sp     += $this->getAttribute('ene_calc_mod2') * 4;
            $sta    += $this->getAttribute('ene_calc_mod2') * 4;
            // <---
            // <---

            // HP, SP e STA --->
            if ($this->id_classe_tipo == 1 || $this->id_classe_tipo == 4) {
                $hp        += (3 * $this->level) * 6;
                $sp        += (3 * $this->level) * 3;
                $sta    += (3 * $this->level) * 6;
            } else {
                $hp        += (3 * $this->level) * 6;
                $sp        += (3 * $this->level) * 6;
                $sta    += (3 * $this->level) * 3;
            }

            // Então, sabe o comentario sobre a conta de hp? Pois é, essa daqui é a modificação, no dia que alguem se arrepender,
            // é so comentar essa daqui e e descomentar a de cima
            $arv_hp_inc        = percent($this->getAttribute('hp_arv'),  $hp);
            $arv_sp_inc        = percent($this->getAttribute('sp_arv'), $sp);
            $arv_sta_inc    = percent($this->getAttribute('sta_arv'), $sta);

            $item_hp_inc    = percent($this->getAttribute('hp_item')  + $this->getAttribute('hp_mod'),  $hp);
            $item_sp_inc    = percent($this->getAttribute('sp_item')  + $this->getAttribute('sp_mod'),  $sp);
            $item_sta_inc    = percent($this->getAttribute('sta_item') + $this->getAttribute('sta_mod'), $sta);

            $hp        += $arv_hp_inc  + $item_hp_inc;
            $sp        += $arv_sp_inc  + $item_sp_inc;
            $sta    += $arv_sta_inc + $item_sta_inc;

            $this->setLocalAttribute('hp_arv_calc', $arv_hp_inc);
            $this->setLocalAttribute('sp_arv_calc', $arv_sp_inc);
            $this->setLocalAttribute('sta_arv_calc', $arv_sta_inc);

            $this->setLocalAttribute('hp_item_calc', $item_hp_inc);
            $this->setLocalAttribute('sp_item_calc', $item_sp_inc);
            $this->setLocalAttribute('sta_item_calc', $item_sta_inc);
            // <---

            $this->setLocalAttribute('max_hp',    $hp);
            $this->setLocalAttribute('max_sp',    $sp);
            $this->setLocalAttribute('max_sta',    $sta);

            $this->setLocalAttribute('hp',    absm($hp    - $this->getAttribute('less_hp')));
            $this->setLocalAttribute('sp',    absm($sp    - $this->getAttribute('less_sp')));
            $this->setLocalAttribute('sta',    absm($sta    - $this->getAttribute('less_sta')));

            $this->setLocalAttribute('max_crit_hits', floor($this->getAttribute('conc_calc') / 10));
            $this->setLocalAttribute('max_esq_hits',  floor($this->getAttribute('esq_calc') / 10));
            $this->setLocalAttribute('max_esquiva_hits',  floor($this->getAttribute('esquiva_calc') / 5));

            $this->max_hp            = $hp;
            $this->max_sp            = $sp;
            $this->max_sta        = $sta;

            $this->hp                = absm($hp  - $this->getAttribute('less_hp'));
            $this->sp                = absm($sp  - $this->getAttribute('less_sp'));
            $this->sta            = absm($sta - $this->getAttribute('less_sta'));

            if ((isset($this->id_batalha_multi) && $this->id_batalha_multi) || (isset($this->id_batalha_multi_pvp) && $this->id_batalha_multi_pvp)) {
                $role_id            = Player::getFlag('equipe_role', $this->id);
                $has_specialization    = false;

                if ($role_id != '') {
                    switch ($role_id) {
                        case 0:
                        case 1:
                        case 2:
                        case 3:
                            //case 4:
                        case 5:
                        case 6:
                            $role_lvl    = Player::getFlag('equipe_role_' . $role_id . '_lvl', $this->id);

                            if ($role_lvl > 0) {
                                $item                = Recordset::query('SELECT * FROM item WHERE id_tipo=22 AND id_habilidade=' . $role_id . ' AND ordem=' . $role_lvl)->row_array();
                                $has_specialization    = true;
                            }
                            if ($has_specialization) {
                                $this->setLocalAttribute('atk_magico_calc', $this->getAttribute('atk_magico_calc') + floor(percent($item['atk_magico'], $this->getAttribute('atk_magico_calc'))));
                                $this->setLocalAttribute('atk_fisico_calc', $this->getAttribute('atk_fisico_calc') + floor(percent($item['atk_fisico'], $this->getAttribute('atk_fisico_calc'))));
                                $this->setLocalAttribute('def_magico_calc', $this->getAttribute('def_magico_calc') + floor(percent($item['def_base'], $this->getAttribute('def_magico_calc'))));
                                $this->setLocalAttribute('def_fisico_calc', $this->getAttribute('def_fisico_calc') + floor(percent($item['def_base'], $this->getAttribute('def_fisico_calc'))));
                            }

                            break;
                    }
                }
            }

            // Remover isso caso cause lag
            if (is_a($this, 'Player')) {
                $jutsus_counters = $this->player_jutsus_count_level();

                $this->setLocalAttribute(
                    'fight_power',
                    ($this->level * 100) +
                        ($jutsus_counters[1] * 200) +
                        ($jutsus_counters[2] * 400) +
                        ($jutsus_counters[3] * 600) +
                        ($this->id_graduacao * 1000) +
                        ($this->getAttribute('for_calc_mod') + $this->getAttribute('int_calc_mod') + $this->getAttribute('res_calc_mod')) * 150 +
                        ($this->getAttribute('agi_calc_mod') + $this->getAttribute('con_calc_mod')) * 100 +
                        ($this->getAttribute('nin_calc_mod') + $this->getAttribute('tai_calc_mod') + $this->getAttribute('ken_calc_mod') + $this->getAttribute('gen_calc_mod')) * 200 +
                        ($this->getAttribute('ene_calc_mod') + $this->getAttribute('ene_calc_mod2')) * 200
                );
            } else {
                $this->setLocalAttribute(
                    'fight_power',
                    ($this->level * 100) +
                        ($this->getAttribute('for_calc_mod') + $this->getAttribute('int_calc_mod') + $this->getAttribute('res_calc_mod')) * 150 +
                        ($this->getAttribute('agi_calc_mod') + $this->getAttribute('con_calc_mod')) * 100 +
                        ($this->getAttribute('nin_calc_mod') + $this->getAttribute('tai_calc_mod') + $this->getAttribute('ken_calc_mod') + $this->getAttribute('gen_calc_mod')) * 200 +
                        ($this->getAttribute('ene_calc_mod') + $this->getAttribute('ene_calc_mod2')) * 200
                );
            }
            if (is_a($this, 'Player')) {
                //Missões
                $player_quest_status    = Recordset::query('SELECT (SUM(quest_d)+SUM(quest_c)+SUM(quest_b)+SUM(quest_a)+SUM(quest_s)+SUM(tarefa)) as total FROM player_quest_status WHERE id_player=' . $this->id)->row_array();

                //Bingo Book
                $alvos                    = Recordset::query('SELECT COUNT(id) AS total FROM bingo_book WHERE morto=\'1\' AND id_player=' . $this->id)->row_array();

                $this->setLocalAttribute(
                    'experience_ninja',
                    ($this->vitorias + $this->vitorias_f + $this->derrotas + $this->derrotas_f + $this->empates + $this->empates_npc) * 25 +
                        ($this->vitorias + $this->vitorias_f) * 50 +
                        ($this->vitorias_d) * 5 +
                        ($player_quest_status['total'] * 20) +
                        ($alvos['total'] * 100) +
                        ($this->vitorias_rnd * 100)
                );
            }

            // Aeoooooooo profissão -->
            if (is_a($this, 'Player')) {
                if (!$this->_profession_cache) {
                    $this->_profession_cache    = Recordset::query('SELECT * FROM profissao_ativa WHERE id_player_alvo=' . $this->id)->result_array();
                }

                $active_professions    = $this->_profession_cache;

                if (is_array($active_professions) && sizeof($active_professions)) {
                    foreach ($active_professions as $active_profession) {
                        $profession    = Recordset::query('SELECT * FROM profissao WHERE id=' . $active_profession['id_profissao'])->row();
                        $now        = now();
                        $has_active    = false;
                        $limit        = strtotime('+10 minute', strtotime($active_profession['data_ins']));

                        if ($now < $limit) {
                            if ($profession->cozinheiro_ativo) {
                                $formula    = 20;
                                $this->bonus_vila['ramen_preco']    += $formula;
                            }

                            if ($profession->ferreiro_ativo) {
                                $formula    = 20; // + (5 * $active_profession['level'] - 1);

                                $this->bonus_vila['ns_dano_longo']    += $formula;
                                $this->bonus_vila['ns_dano_curto']    += $formula;
                            }

                            if ($profession->cacador_ativo) {
                                $this->bonus_profissao['bb_cacador']    = 1;
                            }

                            if ($profession->instrutor_ativo) {
                                $formula    = 10; // + (5 * $active_profession['level'] - 1);

                                $this->setLocalAttribute('atk_magico_calc', $basePlayer->getAttribute('atk_magico_calc') + percent($formula, $basePlayer->getAttribute('atk_magico_calc')));
                                $this->setLocalAttribute('atk_fisico_calc', $basePlayer->getAttribute('atk_fisico_calc') + percent($formula, $basePlayer->getAttribute('atk_magico_calc')));
                            }

                            if ($profession->aventureiro_ativo) {
                                $formula    = 10; // + (5 * $active_profession['level'] - 1);

                                $this->bonus_vila['dojo_exp_npc']    += $formula;
                                $this->bonus_vila['dojo_exp_pvp']    += $formula;
                            }

                            $this->id_profissao_ativa    = $profession->id;
                        }
                    }
                }
            }
            // <--

            $this->do_key_mapping();

            if (isset($this->npc_vila) && $this->npc_vila) {
                $this->setLocalAttribute('max_hp',  $this->npc_vila_at['hp']);
                $this->setLocalAttribute('max_sp',  $this->npc_vila_at['sp']);
                $this->setLocalAttribute('max_sta', $this->npc_vila_at['sta']);

                $this->setLocalAttribute('hp',  $this->npc_vila_at['hp']  - $this->npc_vila_at['less_hp']);
                $this->setLocalAttribute('sp',  $this->npc_vila_at['sp']  - $this->npc_vila_at['less_sp']);
                $this->setLocalAttribute('sta', $this->npc_vila_at['sta'] - $this->npc_vila_at['less_sta']);
            }

            if (isset($this->npc_vila) && $this->npc_guerra_s) {
                $this->update_npc_war_stats();
            }
        }
    }
