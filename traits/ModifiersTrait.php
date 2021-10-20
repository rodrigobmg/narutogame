<?php
    trait ModifiersTrait {
        function getModifiers() {
            if (get_class($this) === 'Player') {
                $var = "_MODIFIERS_PLAYER_" . $this->id;
            } else {
                $var = "_MODIFIERS_NPC_" . $this->batalha_multi_pos;

                if (isset($this->batalha_multi_id)) {
                    $var .= '_' . $this->batalha_multi_id;
                }

                if (isset($this->batalha_id)) {
                    $var .= '_' . $this->batalha_id;
                }
            }

            $modifiers = SharedStore::G($var);

            return is_array($modifiers) ? $modifiers : array();
        }

        function clearModifiers() {
            $this->saveModifiers([]);
        }

        function saveModifiers(array $arModifiers) {
            if (get_class($this) === 'Player') {
                $var = "_MODIFIERS_PLAYER_" . $this->id;
            } else {
                $var = "_MODIFIERS_NPC_" . $this->batalha_multi_pos;

                if (isset($this->batalha_multi_id)) {
                    $var .= '_' . $this->batalha_multi_id;
                }

                if (isset($this->batalha_id)) {
                    $var .= '_' . $this->batalha_id;
                }
            }

            SharedStore::S($var, $arModifiers);
        }

        function addModifier($modifier, $level, $direction, $o_direction = NULL, $esp_lvl = NULL, $remote_id = NULL, $force_id = NULL) {
            $arModfiers = $this->getModifiers();
            $uid        = 0;
            $enhancer    = 0;
            $source_id    = 0;

            if (is_numeric($modifier)) {
                $item = Recordset::query("SELECT * FROM item WHERE id=" . (int)$modifier, true)->row_array();
            } else {
                if (!isset($modifier->id)) {
                    return;
                }

                $uid            = $modifier->uid;
                $enhancer        = $modifier->aprimoramento;
                $source_id        = $modifier->playerID;

                if (isset($modifier->det_inc)) {
                    $det_inc    = $modifier->det_inc;
                } else {
                    $det_inc     = 0;
                }

                $item            = Recordset::query("SELECT * FROM item WHERE id=" . $modifier->id, true)->row_array();
                $item['turnos']    = $modifier->cooldown;
                $modifier        = $item['id'];

                // Determinação
                if ($item['id_tipo'] == 32) {
                    $item['turnos']    = 9999;
                }
            }

            $modifierExists = false;

            if ($item['ordem']) {
                $arValidModifiers = array();

                foreach ($arModfiers as $cModifier) {
                    $cItem = new Recordset("SELECT * FROM item WHERE id=" . (int)$cModifier['id'], true);
                    $cItem = $cItem->row_array();

                    if ($cItem['ordem']) {
                        if ($item['id_tipo'] == $cItem['id_tipo'] && $item['ordem'] > $cItem['ordem']) {
                            $arValidModifiers[] = array(
                                "id"            => $modifier,
                                "turns"            => 99999, //$item['turnos'],
                                "direction"        => 0,
                                "level"            => $level,
                                "o_direction"    => 0,
                                "image"            => $item['imagem'],
                                "ord"            => true,
                                "conv"            => false,
                                'uid'            => $uid,
                                'enhancer'        => $enhancer,
                                'source_id'        => $source_id
                            );

                            $modifierExists = true;
                        } elseif ($item['id_tipo'] == $cItem['id_tipo'] && $item['ordem'] <= $cItem['ordem']) {
                            $arValidModifiers[] = $cModifier;

                            $modifierExists = true;
                        } else {
                            $arValidModifiers[] = $cModifier;
                        }
                    } else {
                        $arValidModifiers[] = $cModifier;
                    }
                }

                if (!$modifierExists) {
                    $arValidModifiers[] = array(
                        "id"            => $modifier,
                        "direction"     => 0,
                        "turns"            => 99999, //$item['turnos'],
                        "level"            => $level,
                        "o_direction"    => 0,
                        'image'            => $item['imagem'],
                        'ord'            => 1,
                        'conv'            => false,
                        'uid'            => $uid,
                        'enhancer'        => $enhancer,
                        'source_id'        => $source_id
                    );
                }

                $this->saveModifiers($arValidModifiers, $this->id);
            } else {
                // Se o modificador ja existe soma os turnos
                foreach ($arModfiers as $cModifier) {
                    if ($cModifier['id'] == $modifier && $cModifier['direction'] == $direction) {
                        $modifierExists = true;

                        $cModifier['turns'] += $item['turnos'] - 1;
                    }
                }

                // Se o modificador não existe
                if (!$modifierExists) {
                    $arModfiers[] = array(
                        "id"            => $modifier,
                        "direction"     => $direction,
                        "turns"         => $item['turnos'] - 1,
                        "level"         => $level,
                        "o_direction"   => $o_direction,
                        "esp_lvl"       => $esp_lvl,
                        "remote"        => $remote_id,
                        'image'         => $item['imagem'],
                        'ord'           => 0,
                        'conv'          => $item['id_tipo'] == 32,
                        'uid'           => $uid,
                        'enhancer'      => $enhancer,
                        'source_id'     => $source_id,
                        'det_inc'       => $det_inc
                    );
                }

                $this->saveModifiers($arModfiers, $this->id);
            }
        }

        function rotateModifiers() {
            $arModfiers = $this->getModifiers();
            $arModifiersActive = array();

            foreach ($arModfiers as $modifier) {
                if ($modifier['turns'] > 0) {
                    $modifier['turns']--;

                    $arModifiersActive[] = $modifier;
                }
            }

            $this->saveModifiers($arModifiersActive);
        }

        function parseModifiers() {
            $arModifiers    = $this->getModifiers($this);

            $tai            = 0;
            $ken            = 0;
            $nin            = 0;
            $gen            = 0;
            $agi            = 0;
            $con            = 0;
            $ene            = 0;
            $for            = 0;
            $int            = 0;
            $res            = 0;

            $atk_fisico     = 0;
            $atk_magico     = 0;
            $def_fisico     = 0;
            $def_magico     = 0;
            $def_base       = 0;

            $prec_fisico    = 0;
            $prec_magico    = 0;
            $crit_min       = 0;
            $crit_max       = 0;
            $crit_total     = 0;
            $esq_min        = 0;
            $esq_max        = 0;
            $esq_total      = 0;
            $esq            = 0;
            $det            = 0;
            $conv           = 0;
            $esquiva        = 0;
            $conc           = 0;
            $hp             = 0;
            $sp             = 0;
            $sta            = 0;

            if (get_class($this) === 'Player') {
                $pass     = false;

                if (!isset($this->quick)) {
                    $this->quick    = false;
                }

                if (!$this->quick) {
                    if ($this->getAttribute('id_batalha')) {
                        $pass = true;
                    }

                    if ($this->getAttribute('id_batalha_multi')) {
                        $pass = true;
                    }

                    if ($this->getAttribute('id_batalha_multi_pvp')) {
                        $pass = true;
                    }
                }
            } else {
                $pass = true;
            }

            $this->_calc_atp['atk_fisico_mod'] = 0;
            $this->_calc_atp['atk_magico_mod'] = 0;
            $this->_calc_atp['def_fisico_mod'] = 0;
            $this->_calc_atp['def_magico_mod'] = 0;
            $this->_calc_atp['def_base_mod']   = 0;

            //echo '// PASS -> ' . (int)$pass . PHP_EOL;

            if ($pass) {
                //echo '// SZ_MO -> ' . sizeof($arModifiers) . PHP_EOL;
                foreach ($arModifiers as $modifier) {
                    $mod    = Recordset::query("SELECT * FROM item_modificador WHERE id_item=" . (int)$modifier['id'], true)->row_array();

                    // Apply enhancer
                    if ($modifier['uid'] && $modifier['enhancer']) {
                        foreach ($modifier['enhancer'] as $_ => $enhancer) {
                            $enhancer_base = Recordset::query('SELECT * FROM item WHERE id=' . $enhancer, true)->row_array();
                            $enhancer_data = Recordset::query("SELECT * FROM item_modificador WHERE id_item=" . $enhancer, true);
                            $slot          = $_;

                            if ($enhancer_data->num_rows) {
                                foreach ($enhancer_data->row_array() as $_ => $value) {
                                    if (!$value) {
                                        continue;
                                    }

                                    if ($enhancer_base['tempo_espera'] == $slot) {
                                        $mod[$_] += $value;
                                    } else {
                                        $mod[$_] += $value / 2;
                                    }
                                }
                            }
                        }
                    }

                    $item    = Recordset::query("SELECT * FROM item WHERE id=" . (int)$modifier['id'], true)->row_array();

                    if ($modifier['ord'] && ($item['id_tipo'] == 16 || $item['id_tipo'] == 21)) {
                        $item_player    = Recordset::query('SELECT * FROM player_modificadores WHERE id_player=' . $modifier['source_id'] . ' AND id_tipo=' . $item['id_tipo']);

                        if ($item_player->num_rows) {
                            $item_player    = $item_player->row_array();

                            foreach ($item_player as $k => $v) {
                                if ($k == 'id_player' || $k == 'id_tipo') {
                                    continue;
                                }
                                $item_player[$k]    *= $item['ordem'];
                            }

                            // os campos da item_player substituem os da item
                            $item    = array_merge($item, $item_player);
                            /*
                                echo '<pre>';
                                print_r($item_player);
                                print_r($item);
                                die();
                                */
                        }
                    }

                    if ($modifier['direction'] == 0) {
                        $dir = "self_";
                    } else {
                        $dir = "target_";
                    }

                    if ((isset($modifier['ord']) && $modifier['ord']) || (isset($modifier['conv']) && $modifier['conv'])) {
                        if (isset($modifier['conv']) && $modifier['conv']) {
                            $bonuses    = array(
                                'tai', 'ken', 'nin', 'gen', 'agi', 'ene', 'con', 'inte',
                                'forc', 'res', 'atk_fisico', 'atk_magico', 'def_base', 'def_fisico', 'def_magico',
                                'prec_fisico', 'prec_magico', 'crit_min', 'crit_max', 'esq_min', 'crit_total',
                                'esq_max', 'esq_total', 'esq', 'det', 'conv', 'esquiva', 'conc', 'bonus_hp', 'bonus_sp', 'bonus_sta'
                            );

                            foreach ($bonuses as $bonus) {
                                if ($item[$bonus] > 0) {
                                    $item[$bonus]    = $modifier['det_inc'];
                                }
                            }
                        }

                        if ($item['id_tipo'] == 23 || $item['id_tipo'] == 39) {
                            $tai        += percent($item['tai'],  $this->getAttribute('tai_raw'));
                            $ken        += percent($item['ken'],  $this->getAttribute('ken_raw'));
                            $nin        += percent($item['nin'],  $this->getAttribute('nin_raw'));
                            $gen        += percent($item['gen'],  $this->getAttribute('gen_raw'));
                            $agi        += percent($item['agi'],  $this->getAttribute('agi_raw'));
                            $ene        += percent($item['ene'],  $this->getAttribute('ene_raw'));
                            $con        += percent($item['con'],  $this->getAttribute('con_raw'));
                            $int        += percent($item['inte'], $this->getAttribute('int_raw'));
                            $for        += percent($item['forc'], $this->getAttribute('for_raw'));
                            $res         += percent($item['res'],  $this->getAttribute('res_raw'));
                        } else {
                            $tai        += $item['tai'];
                            $ken        += $item['ken'];
                            $nin        += $item['nin'];
                            $gen        += $item['gen'];
                            $agi        += $item['agi'];
                            $ene        += $item['ene'];
                            $con        += $item['con'];
                            $int        += $item['inte'];
                            $for        += $item['forc'];
                            $res         += $item['res'];
                        }

                        if (!$item['tipo_bonus']) {
                            $this->_calc_atp['atk_fisico_mod'] += $item['atk_fisico'];
                            $this->_calc_atp['atk_magico_mod'] += $item['atk_magico'];
                            $this->_calc_atp['def_fisico_mod'] += $item['def_fisico'];
                            $this->_calc_atp['def_magico_mod'] += $item['def_magico'];
                            $this->_calc_atp['def_base_mod']   += $item['def_base'];
                        } else {
                            $atk_fisico += $item['atk_fisico'];
                            $atk_magico += $item['atk_magico'];
                            $def_fisico += $item['def_fisico'];
                            $def_magico += $item['def_magico'];
                            $def_base   += $item['def_base'];
                        }

                        $prec_fisico += $item['prec_fisico'];
                        $prec_magico += $item['prec_magico'];
                        $crit_min    += $item['crit_min'];
                        $crit_max    += $item['crit_max'];
                        $crit_total  += $item['crit_total'];
                        $esq_min     += $item['esq_min'];
                        $esq_max     += $item['esq_max'];
                        $esq_total   += $item['esq_total'];
                        $esq         += $item['esq'];
                        $det         += $item['det'];
                        $conv        += $item['conv'];
                        $esquiva     += $item['esquiva'];
                        $conc        += $item['conc'];
                        $hp          += $item['bonus_hp'];
                        $sp          += $item['bonus_sp'];
                        $sta         += $item['bonus_sta'];

                        //echo '// ORD 1 -> ' . $item['id'] . PHP_EOL;
                    } else {
                        //Adicionado
                        $percent = 0;

                        if (isset($modifier['level']) && $modifier['level'] > 1) {
                            if ($modifier['level'] == 2) {
                                $percent = 20;
                            } elseif ($modifier['level'] == 3) {
                                $percent = 40;
                            }

                            $vars = array('tai', 'ken', 'nin', 'gen', 'agi', 'ene', 'con', 'inte', 'forc', 'res', 'esq', 'det', 'conv', 'esquiva', 'conc', 'atk_fisico', 'atk_magico', 'def_base', 'def_fisico', 'def_magico');

                            foreach ($vars as $var) {
                                $mod[$dir . $var] += round(percent($percent, $mod[$dir . $var]));
                            }
                        }

                        $tai        += $mod[$dir . 'tai'];
                        $tai        += $mod[$dir . 'ken'];
                        $nin        += $mod[$dir . 'nin'];
                        $gen        += $mod[$dir . 'gen'];
                        $agi        += $mod[$dir . 'agi'];
                        $ene        += $mod[$dir . 'ene'];
                        $con        += $mod[$dir . 'con'];
                        $int        += $mod[$dir . 'inte'];
                        $for        += $mod[$dir . 'forc'];
                        $res         += $mod[$dir . 'res'];

                        if (!$item['tipo_bonus']) {
                            $this->_calc_atp['atk_fisico_mod']    += $mod[$dir . 'atk_fisico'];
                            $this->_calc_atp['atk_magico_mod']    += $mod[$dir . 'atk_magico'];
                            $this->_calc_atp['def_fisico_mod']    += $mod[$dir . 'def_fisico'];
                            $this->_calc_atp['def_magico_mod']    += $mod[$dir . 'def_magico'];
                            $this->_calc_atp['def_base_mod']        += $mod[$dir . 'def_base'];
                        } else {
                            $atk_fisico        += $mod[$dir . 'atk_fisico'];
                            $atk_magico        += $mod[$dir . 'atk_magico'];
                            $def_fisico        += $mod[$dir . 'def_fisico'];
                            $def_magico        += $mod[$dir . 'def_magico'];
                            $def_base        += $mod[$dir . 'def_base'];
                        }

                        $prec_fisico    += $mod[$dir . 'prec_fisico'];
                        $prec_magico    += $mod[$dir . 'prec_magico'];
                        $crit_min        += $mod[$dir . 'crit_min'];
                        $crit_max        += $mod[$dir . 'crit_max'];
                        $crit_total        += $mod[$dir . 'crit_total'];
                        $esq_min        += $mod[$dir . 'esq_min'];
                        $esq_max        += $mod[$dir . 'esq_max'];
                        $esq_total        += $mod[$dir . 'esq_total'];
                        $esq            += $mod[$dir . 'esq'];
                        $det            += $mod[$dir . 'det'];
                        $conv            += $mod[$dir . 'conv'];
                        $esquiva        += $mod[$dir . 'esquiva'];
                        $conc            += $mod[$dir . 'conc'];
                    }
                }
            }

            $this->setLocalAttribute('tai_mod', $tai);
            $this->setLocalAttribute('ken_mod', $ken);
            $this->setLocalAttribute('nin_mod', $nin);
            $this->setLocalAttribute('gen_mod', $gen);
            $this->setLocalAttribute('agi_mod', $agi);
            $this->setLocalAttribute('con_mod', $con);
            $this->setLocalAttribute('ene_mod', $ene);
            $this->setLocalAttribute('for_mod', $for);
            $this->setLocalAttribute('int_mod', $int);
            $this->setLocalAttribute('res_mod', $res);

            $this->setLocalAttribute('atk_fisico_mod',    $atk_fisico);
            $this->setLocalAttribute('atk_magico_mod',    $atk_magico);
            $this->setLocalAttribute('def_fisico_mod',    $def_fisico);
            $this->setLocalAttribute('def_magico_mod',    $def_magico);
            $this->setLocalAttribute('def_base_mod',        $def_base);

            $this->setLocalAttribute('prec_fisico_mod',     $prec_fisico);
            $this->setLocalAttribute('prec_magico_mod',     $prec_magico);
            $this->setLocalAttribute('crit_min_mod',         $crit_min);
            $this->setLocalAttribute('crit_max_mod',         $crit_max);
            $this->setLocalAttribute('crit_total_mod',     $crit_total);
            $this->setLocalAttribute('esq_min_mod',         $esq_min);
            $this->setLocalAttribute('esq_max_mod',         $esq_max);
            $this->setLocalAttribute('esq_total_mod',         $esq_total);
            $this->setLocalAttribute('esq_mod',             $esq);
            $this->setLocalAttribute('det_mod',             $det);
            $this->setLocalAttribute('conv_mod',             $conv);
            $this->setLocalAttribute('esquiva_mod',         $esquiva);
            $this->setLocalAttribute('conc_mod',             $conc);
            $this->setLocalAttribute('hp_mod',             $hp);
            $this->setLocalAttribute('sp_mod',             $sp);
            $this->setLocalAttribute('sta_mod',             $sta);
        }
    }
