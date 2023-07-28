<?php
class utility { //extends Zend_Form
    /* private $name;

      public function __construct($name) {
      $this->name = $name;
      }
      public function __destruct() {
      echo 'Destruyendo: ', $this->name, PHP_EOL;
      } */
    /**
     *  Distribucion base de columnas para el dise�o del formulario
     *  @param    integer   $pCols              - Numero de Columnas (pixeles)
     *  @param    integer   $pAncho             - Ancho de las Celdas (pixeles)
     *  @return   string    $cCadena            - Cadena html con las celdas de base para el formulario
     */
    function fnColumnas($pCols, $pAncho) {
        $cCadena = "<tr height=0>";
        for ($xi = 0; $xi < $pCols; $xi++) {
            $cCadena .= "<td width = '$pAncho'></td>";
        }
        $cCadena .= "</tr>";
        return $cCadena;
    }
    /**
     * 
     * Alert estilo Dojo y Confirm estilo Dojo
     * @param string      $pMensaje              - Mensaje a Mostrar
     * @param string      $pGraphics             - Ruta para grafico del mensaje
     * @param integer     $pAncho                - Ancho del Cuadro de mensaje
     * @param string      $pCerrar               - Id del Cuadro de mensaje que se debe cerrar
     */
    function fnDjAlerta($pMensaje, $pCierra, $pRuta) {
        $vError = explode('~', $pMensaje);
        $cCadena = "";
        foreach ($vError as $cError) {
            if (strlen($cError) > 0) {
                $cCadena .= "\\n" . $cError;
            }
        }
        ?>
        <script type="text/javascript">
            alert("<?php echo $cCadena ?>");
        </script>
        <?php
        if (strlen($pRuta) > 0) {
            switch ($pCierra) {
                case 1:
                    ?>
                    <script type="text/javascript">
                        document.location = "<?php echo $pRuta ?>";
                    </script> 
                    <?php
                    break;
                case 2:
                    ?>
                    <script type="text/javascript">
                        window.opener.location = "<?php echo $pRuta ?>";
                    </script> 
                    <?php
                    break;
                case 3:
                    ?>
                    <script type="text/javascript">
                        parent.document.location = "<?php echo $pRuta ?>";
                    </script> 
                    <?php
                    break;
            }
            if ($pCierra == 2) {
                ?>
                <script type="text/javascript">
                    window.close();
                </script>
                <?php
            }
        } else {
            if ($pCierra == 2) {
                ?>
                <script type="text/javascript">
                    window.close();
                </script>
                <?php
            }
        }
    }
    /**
     * Direccion Ip del usuario actual
     * @return    string   $cIp               - Direccion Ip
     * 
     */
    function fnIpCheck() {
        /*
          This function checks if user is coming behind proxy server. Why is this important?
          If you have high traffic web site, it might happen that you receive lot of traffic
          from the same proxy server (like AOL). In that case, the script would count them all as 1 user.
          This function tryes to get real IP address.
          Note that getenv() function doesn't work when PHP is running as ISAPI module
         */
        $cIp = "";
        if (getenv('HTTP_CLIENT_IP')) {
            $cIp = getenv('HTTP_CLIENT_IP');
        } elseif (getenv('HTTP_X_FORWARDED_FOR')) {
            $cIp = getenv('HTTP_X_FORWARDED_FOR');
        } elseif (getenv('HTTP_X_FORWARDED')) {
            $cIp = getenv('HTTP_X_FORWARDED');
        } elseif (getenv('HTTP_FORWARDED_FOR')) {
            $cIp = getenv('HTTP_FORWARDED_FOR');
        } elseif (getenv('HTTP_FORWARDED')) {
            $cIp = getenv('HTTP_FORWARDED');
        } else {
            $cIp = $_SERVER['REMOTE_ADDR'];
        }
        return $cIp;
    }
    /**
     * En caso de utilizar idiomas en archivos separados
     * 
     */
    function fnIdiomas() {
        $cIdioma = $_COOKIE['kIdioma'];
        $translate = new Zend_Translate(
            array('adapter' => 'tmx',
            'content' => APPLICATION_PATH . '/configs/idiomas.tmx',
            'locale' => '$cIdioma'
            )
        );
    }
    /**
     * 
     * Calculo del Digito de Verificacion
     * @param  integer      $pNit                         - Nit o CC
     * @return integer      $nResdv                       - Digito de Verificacion
     */
    function fnDigitoVerificacion($pNit) {
        $nResdv = 0;
        $nLnit = strlen($pNit);
        if ($nLnit > 0) {
            $nSuma = 0;
            $vNit = array(97, 89, 83, 79, 73, 71, 67, 59, 53, 47, 43, 41, 37, 29, 23, 19, 17, 13, 7, 3);
            $nIni = count($vNit) - $nLnit;
            for ($x = 0; $x < $nLnit; $x++) {
                $nVlr = 1 * (substr($pNit, $x, 1));
                $nSuma = $nSuma + ($nVlr * $vNit[$nIni]);
                $nIni += 1;
            }
            $nResdv = $nSuma % 11;
            if ($nResdv > 1) {
                $nResdv = 11 - $nResdv;
            }
        } else {
            $nResdv = '';
        }
        $nResdv = trim($nResdv);
        return $nResdv;
        //return '9';
    }
    /**
     * Cadena Aleatoria php
     * @param   integer     $pLength                  - Longitud de la cadena resultante
     * @return  string      $cResult                  - Cadena Aleatoria
     */
    function fnCadenaAleatoria($pLength = 8) {
        //$cCaracteres = "abcdefghijklmnopqrstuxyvwzABCDEFGHIJKLMNOPQRSTUXYVWZ+-*#&@!?";
        $cCaracteres = "abcdefghijklmnopqrstuxyvwzABCDEFGHIJKLMNOPQRSTUXYVWZ";
        $nCaracteres = strlen($cCaracteres);
        $cResult = "";
        for ($x = 0; $x < $pLength; $x++) {
            $nIndex = mt_rand(0, $nCaracteres - 1);
            $cResult .= $cCaracteres[$nIndex];
        }
        return $cResult;
    }
    function fnFecha($date, $format = 'YYYY-MM-DD') {
        if (strlen($date) >= 8 && strlen($date) <= 10) {
            $separator_only = str_replace(array('M', 'D', 'Y'), '', $format);
            $separator = $separator_only[0];
            if ($separator) {
                $regexp = str_replace($separator, "\\" . $separator, $format);
                $regexp = str_replace('MM', '(0[1-9]|1[0-2])', $regexp);
                $regexp = str_replace('M', '(0?[1-9]|1[0-2])', $regexp);
                $regexp = str_replace('DD', '(0[1-9]|[1-2][0-9]|3[0-1])', $regexp);
                $regexp = str_replace('D', '(0?[1-9]|[1-2][0-9]|3[0-1])', $regexp);
                $regexp = str_replace('YYYY', '\d{4}', $regexp);
                $regexp = str_replace('YY', '\d{2}', $regexp);
                if ($regexp != $date && preg_match('/' . $regexp . '$/', $date)) {
                    foreach (array_combine(explode($separator, $format), explode($separator, $date)) as $key => $value) {
                        if ($key == 'YY')
                            $year = '20' . $value;
                        if ($key == 'YYYY')
                            $year = $value;
                        if ($key[0] == 'M')
                            $month = $value;
                        if ($key[0] == 'D')
                            $day = $value;
                    }
                    if (checkdate($month, $day, $year))
                        return true;
                }
            }
        }
        return false;
    }
    /**
     * 
     * Semana del A�o de una Fecha en particular
     * @param     date     $pFecha                   - Fecha
     * @return    integer  $wn                       - Semana del A�o
     */
    function fnSemana($pFecha) {
        $di = 0 + substr($pFecha, 8, 2);
        $me = 0 + substr($pFecha, 5, 2);
        $an = 0 + substr($pFecha, 0, 4);
        $wn = strftime("%W", mktime(0, 0, 0, $me, $di, $an));
        $wn = $wn + 0;
        $pridianio = getdate(mktime(0, 0, 0, 1, 1, $an));

        if ($pridianio['wday'] != 1) {
            $wn = $wn + 1;
            if ($wn == 0 || $wn == '' || !$wn) {
                $wn = 0;
                //return $wn;
            }
            return $wn;
        } else {
            if ($wn == 0 || $wn == '' || !$wn) {
                $wn = 0;
            }
            return $wn;
        }
    }
    /**
     * 
     * Dia de la Semana
     * @param date      $pFechai              - Fecha a calcular
     */
    function fnDiaSemana($pFechai) {
        $nDia = 0 + (substr($pFechai, 8, 2));
        $nMes = 0 + (substr($pFechai, 5, 2));
        $nAno = 0 + (substr($pFechai, 0, 4));
        $dDate1 = mktime(0, 0, 0, $nMes, $nDia, $nAno);
        $nDw = strftime("%w", $dDate1);
        return $nDw;
    }
    /**
     * 
     * Agregar dias a Fecha dada
     * @param date $pFe1       - Fecha a calcular
     * @param int  $pInc       - Dias a agregar
     * @return date $dDiaMas   - Nueva Fecha
     */
    function fnAgregaDia($pFe1, $pInc) {
        $nAno = 0 + substr($pFe1, 0, 4);
        $nMes = 0 + substr($pFe1, 5, 2);
        $nDia = 0 + substr($pFe1, 8, 2);
        //$nDias = 1;
        $dDiaMas = date("Y-m-d", mktime(0, 0, 0, $nMes, $nDia + $pInc, $nAno));
        return $dDiaMas;
    }
    /**
     * 
     * Agregar n Meses a Fecha
     * @param date      $pDate                 - Fecha para Calculo
     * @param integer   $pAdd                  - Meses a Agregar
     */
    function fnAgregarMes($pDate, $pAdd) {
        $dDate = $pDate;
        $cReturnDateFormat = "Y-m-d";
        $pDate = strtotime($pDate);
        if ($pDate !== -1) {
            $pDate = getdate($pDate);
            $pDate['mon'] += $pAdd;
            $dNvaFecha = (date($cReturnDateFormat, mktime(0, 0, 0, $pDate['mon'], $pDate['mday'], $pDate['year'])));
            if (substr($dDate, 8, 2) != substr($dNvaFecha, 8, 2)) {
                $dNvaFecha = $this->fnAgregaDia(substr($dNvaFecha, 0, 8) . "01", -1);
            }
            return $dNvaFecha;
        }
        //return false;
    }
    /**
     * 
     * Dias entre dos Fechas
     * @param date $pFecha1            - Fecha Inicial
     * @param date $pFecha2            - Fecha Final
     */
    function fnDiasEntreJUN302023($pFecha1, $pFecha2) {
        $dFecha1 = substr($pFecha1, 8, 2) . "-" . substr($pFecha1, 5, 2) . "-" . substr($pFecha1, 0, 4);
        $dFecha2 = substr($pFecha2, 8, 2) . "-" . substr($pFecha2, 5, 2) . "-" . substr($pFecha2, 0, 4);
        $dia1 = strtok($dFecha1, "-");
        $mes1 = strtok("-");
        $anyo1 = strtok("-");

        $dia2 = strtok($dFecha2, "-");
        $mes2 = strtok("-");
        $anyo2 = strtok("-");
        $nNumDias = 0;
        if ($anyo1 < $anyo2) {
            $dias_anyo1 = date("z", mktime(0, 0, 0, 12, 31, $anyo1)) - date("z", mktime(0, 0, 0, $mes1, $dia1, $anyo1));
            $dias_anyo2 = date("z", mktime(0, 0, 0, $mes2, $dia2, $anyo2));
            $nNumDias = $dias_anyo1 + $dias_anyo2;
        } else {
            $nNumDias = date("z", mktime(0, 0, 0, $mes2, $dia2, $anyo2)) - date("z", mktime(0, 0, 0, $mes1, $dia1, $anyo1));
        }
        return $nNumDias;
    }
    /**
     * 
     * Dias entre dos Fechas
     * @param date $pFec1            - Fecha Inicial
     * @param date $pFec2            - Fecha Final
     */
    function fnDiasEntre($pFec1, $pFec2) {
        if ($pFec2 > $pFec1) {
            $date_diff = strtotime($pFec2) - strtotime($pFec1);
            return round($date_diff / 86400);
        } else {
            if ($pFec1 == $pFec2) {
                return 0;
            } else {
                $date_diff = strtotime($pFec1) - strtotime($pFec2);
                return round($date_diff / 86400) * (-1);
            }
        }
    }
    /**
     * 
     * Dias entre dos Fechas
     * @param date $pFecha1            - Fecha Inicial
     * @param date $pFecha2            - Fecha Final
     */
    function fnDiasCalHab($pF1, $pF2, $pDb) {
        $n1 = $this->fnDiasEntre($pF1, $pF2);
        $n2 = $n1;
        if ($n1 == 0) {
            $n1 = 1;
            $n2 = 1;
        }
        if ($n1 > 1) {
            for ($x = 1; $x <= $n1; $x++) {
                $cT = $this->fnAgregaDia($pF1, $x);
                if ($this->fnDiaSemana($cT) == 6) {
                    $n2--;
                } else {
                    $qS = $pDb->select()
                        ->from('impo1020')
                        ->where('difidxxx=?', "$cT");
                    $xR = $pDb->fetchRow($qS);
                    if ($xR != null) {
                        $n2--;
                    }
                }
            }
        }
        return array($n1, $n2);
    }
    /**
     * 
     * Dias entre dos Fechas
     * @param date $pFecha1            - Fecha Inicial
     * @param date $pFecha2            - Fecha Final
     */
    function fnDiasCalHab2($pF1, $pF2, $pDb) {
        $n1 = $this->fnDiasEntre($pF1, $pF2);
        $n2 = $n1;
        if ($n1 == 0) {
            $n1 = 1;
            $n2 = 1;
        }
        $n2 = 0;
        if ($n1 > 1) {
            for ($x = 1; $x <= $n1; $x++) {
                $cT = $this->fnAgregaDia($pF1, $x);
                $ds = $this->fnDiaSemana($cT);
                if ($ds == 6 || $ds == 0) {
                    $n2++;
                } else {
                    $qS = $pDb->select()
                        ->from('impo1020')
                        ->where('difidxxx=?', "$cT");
                    $xR = $pDb->fetchRow($qS);
                    if ($xR != null) {
                        $n2++;
                    }
                }
            }
            $n1 = $n1 - $n2;
        }
        return array($n1, $n2);
    }
    /**
     * 
     * Dias entre dos Fechas
     * @param date $pFecha1            - Fecha Inicial
     * @param date $pFecha2            - Fecha Final
     */
    function fnDiasHabAtr($cuantos, $pFec, $pDb) {
        $n1 = date("Y-m-d");
        $nv = 0;
        $n2 = 0;
        for ($x = 1; $x < 100; $x++) {
            $cT = $this->fnAgregaDia($pFec, ($x * (-1)));
            $ds = $this->fnDiaSemana($cT);
            if ($ds == 0 || $ds == 6) {
                //$n2--;
            } else {
                $qS = $pDb->select()
                    ->from('impo1020')
                    ->where('difidxxx=?', "$cT");
                $xR = $pDb->fetchRow($qS);
                if ($xR != null) {
                    $n2--;
                } else {
                    $nv++;
                    if ($nv > $cuantos) {
                        break;
                    } else {
                        $n1 = $cT;
                    }
                }
            }
        }
        return $n1;
    }
    /**
     * 
     * Dias entre dos Fechas
     * @param date $pFecha1            - Fecha Inicial
     * @param date $pFecha2            - Fecha Final
     */
    function fnDiasAtr($cuantos, $pFec, $pDb) {
        $n1 = date("Y-m-d");
        $nv = 0;
        $n2 = 0;
        for ($x = 1; $x < 100; $x++) {
            $cT = $this->fnAgregaDia($pFec, ($x * (-1)));
            $ds = $this->fnDiaSemana($cT);
            if ($ds == 0 || $ds == 6) {
                //$n2--;
                $nv++;
                if ($nv > $cuantos) {
                    break;
                } else {
                    $n1 = $cT;
                }
            } else {
                /* $qS = $pDb->select()
                  ->from('impo1020')
                  ->where('difidxxx=?', "$cT");
                  $xR = $pDb->fetchRow($qS);
                  if ($xR != null) {
                  $n2--;
                  } else {
                  $nv++;
                  if ($nv > $cuantos) {
                  break;
                  } else {
                  $n1 = $cT;
                  }
                  } */
                $nv++;
                if ($nv > $cuantos) {
                    break;
                } else {
                    $n1 = $cT;
                }
            }
        }
        return $n1;
    }
    /**
     * 
     * N Dias habiles desde una fecha inicial
     * @param date $pF1            - Fecha Inicial
     * @param date $pDias          - Dias a Contar
     * @return array
     */
    function fnFecHab($pF1, $pDias, $pDb) {
        $vRet = '';
        $cont = 0;
        $nFes = 0;
        $nSab = 0;
        $nDom = 0;
        $nOrd = 0;
        for ($x = 0; $x <= 20; $x++) {
            $cT = $this->fnAgregaDia($pF1, $x);
            if ($cont > $pDias) {
                break;
            } else {
                $nDs = $this->fnDiaSemana($cT);
                if ($nDs == 6 || $nDs == 0) {
                    $vRet .= $cT . ",";
                    if ($nDs == 6) {
                        $nSab++;
                    } else {
                        $nDom++;
                    }
                } else {
                    $qS = $pDb->select()
                        ->from('impo1020')
                        ->where('difidxxx=?', "$cT");
                    $xR = $pDb->fetchRow($qS);
                    if ($xR != null) {
                        $vRet .= $cT . ",";
                        $nFes++;
                    } else {
                        $cont++;
                        $vRet .= $cT . ",";
                        $nOrd++;
                        if ($cont > $pDias) {
                            break;
                        }
                    }
                }
            }
        }
        $vFinal = array();
        $vFinal['diasin'] = $vRet;
        $vFinal['festiv'] = $nFes;
        $vFinal['sabado'] = $nSab;
        $vFinal['doming'] = $nDom;
        $vFinal['ordina'] = $nOrd;
        return $vFinal;
    }
    function fnFecEntre($pMenor, $pMedio, $pMayor) {
        $dEnvia = $pMedio;
        $menor = $pMenor . " " . "10:10:10";
        $medio = $pMedio . " " . "10:10:10";
        $mayor = $pMayor . " " . "10:10:10";
        list($femen, $homen) = explode(" ", $menor);
        list($yemen, $memen, $dimen) = explode("-", $femen);
        list($homen, $mimen, $semen) = explode(":", $homen);
        list($femed, $homed) = explode(" ", $medio);
        list($yemed, $memed, $dimed) = explode("-", $femed);
        list($homed, $mimed, $semed) = explode(":", $homed);
        list($femay, $homay) = explode(" ", $mayor);
        list($yemay, $memay, $dimay) = explode("-", $femay);
        list($homay, $mimay, $semay) = explode(":", $homay);
        $segmen = mktime($homen, $mimen, $semen, $memen, $dimen, $yemen);
        $segmed = mktime($homed, $mimed, $semed, $memed, $dimed, $yemed);
        $segmay = mktime($homay, $mimay, $semay, $memay, $dimay, $yemay);
        if ($segmed - $segmen < 0 || $segmay - $segmed < 0) {
            $dEnvia = '';
        }
        return $dEnvia;
    }
    /**
     * 
     * Calculo dia Habil
     * @param date $pDate               - Fecha 
     */
    function fnHabil($pDate, $pDb) {
        $dRetorna = $pDate;
        if ($this->fnDiaSemana($dRetorna) == 6) {
            $dRetorna = $this->fnAgregaDia($dRetorna, 2);
        } else {
            if ($this->fnDiaSemana($dRetorna) == 0) {
                $dRetorna = $this->fnAgregaDia($dRetorna, 1);
            }
        }
        $qSelect = $pDb->select()
            ->from('impo1020')
            ->where('difidxxx=?', "$dRetorna");
        $xResult = $pDb->fetchAll($qSelect);

        //$sqlfest = mysql_query("SELECT ftvidxxx FROM sys00020 WHERE ftvidxxx = \"$dRetorna\" LIMIT 0,1");
        $fila = count($xResult);
        if ($fila > 0) {
            $dRetorna = $this->fnAgregaDia($dRetorna, 1);
            //$sqlfest = mysql_query("SELECT ftvidxxx FROM sys00020 WHERE ftvidxxx = \"$dRetorna\" LIMIT 0,1");
            //$fila = mysql_num_rows($sqlfest);
            $qSelect = $pDb->select()
                ->from('impo1020')
                ->where('difidxxx=?', "$dRetorna");
            $xResult = $pDb->fetchAll($qSelect);
            $fila = count($xResult);
            while ($this->fnDiaSemana($dRetorna) == 6 || $this->fnDiaSemana($dRetorna) == 0 || $fila > 0) {
                $dRetorna = $this->fnAgregaDia($dRetorna, 1);
                //$sqlfest = mysql_query("SELECT ftvidxxx FROM sys00020 WHERE ftvidxxx = \"$dRetorna\" LIMIT 0,1");
                //$fila = mysql_num_rows($sqlfest);
                $qSelect = $pDb->select()
                    ->from('impo1020')
                    ->where('difidxxx=?', "$dRetorna");
                $xResult = $pDb->fetchAll($qSelect);
                $fila = count($xResult);
            }

            //f_Habil($retorna);
        }
        return $dRetorna;
    }
    /**
     * Calculos control Temporales
     * 
     */
    function fnTemporales($flev, $vlimctper, $vlimctcuo, $pDb) {
        $mesesx = 0;
        $diasx = 0;
        $dfcuox = '';
        $vlimctfin = '';
        $vlimctven = '';
        $vlimctdia = 0;
        $vlimctpcu = 0;
        $hoy = date('Y-m-d');
        try {
            if (strlen($flev) == 0 || $flev == '0000-00-00') {
                $vlimctfin = '';
                $vlimctcuo = 0;
                $vlimctven = '';
            } else {
                $nMeses = ($vlimctcuo * $vlimctper);
                $vlimctfin = $this->fnHabil($this->fnAgregarMes($flev, $nMeses), $pDb);
                //$vlimctfin = $this->fnAgregarMes($flev, $nMeses);
                $arlc = array();
                $ny = 0;
                $n = 0;
                $nCuota = $vlimctcuo;
                $vaen = 0;
                $hay = 1;
                if ($nCuota > 1) {
                    $nSaltos = 0;
                    $vaen++;
                    for ($x = 0; $x < $nCuota; $x++) {
                        $nSaltos += $vlimctper;
                        $fcal = $this->fnHabil($this->fnAgregarMes($flev, $nSaltos), $pDb);
                        //$fcal = $this->fnAgregarMes($flev, $nSaltos);
                        $fenter = $this->fnFecEntre($flev, $hoy, $fcal);
                        if ($fenter) {
                            $dias = $this->fnDiasEntre($hoy, $fcal);
                            $hay = 0;
                            $vlimctpcu = $vaen;
                            $vlimctven = $fcal;
                            $vlimctdia = $dias;
                            break;
                        }
                        $vaen++;
                    }
                } else {
                    $vaen = 1;
                    $hay = 0;
                    $nSaltos = $vlimctper;
                    $fcal = $this->fnHabil($this->fnAgregarMes($flev, $nSaltos), $pDb);
                    //$fcal = $this->fnAgregarMes($flev, $nSaltos);
                    $fenter = $this->fnFecEntre($flev, $hoy, $fcal);
                    if (!$fenter) {
                        $hay = 1;
                    } else {
                        $dias = $this->fnDiasEntre($hoy, $fcal);
                        $hay = 0;
                        $vlimctpcu = $vaen;
                        $vlimctven = $fcal;
                        $vlimctdia = $dias;
                    }
                }
                if ($hay == 1) {
                    $vlimctpcu = 0;
                    $vlimctven = '';
                    $vlimctdia = 0;
                }

                /* $resta = $nCuota-$n;
                  $hay = 1;
                  if ($resta > 0){
                  $vaen++;
                  for ($x=0;$x<$resta;$x++){
                  $fcal = $this->fnHabil($this->fnAgregarMes($flev,($vlimctspe * $vaen)));
                  $fenter = $this->fnFecEntre($flev,$hoy,$fcal);
                  if ($fenter){
                  $dias = $this->fnDiasEntre($hoy,$fcal);
                  $hay = 0;
                  $vlimctpcu = $vaen;
                  $vlimctven = $fcal;
                  $vlimctdia = $dias;
                  break;
                  }
                  $vaen++;
                  }
                  }
                  if ($hay == 1){
                  $vlimctpcu = 0;
                  $vlimctven = '';
                  $vlimctdia = 0;
                  } */
            }
        } catch (Zend_Exception $e) {
            echo "Captura de excepcion: " . __LINE__ . " " . get_class($e) . " " . $e->getMessage() . "<br>";
        }
        return $vlimctven . "~" . $vlimctpcu . "~" . $vlimctdia . "~" . $vlimctfin;
    }
    /**
     * Mapeo caracteres especiales para pdf
     * @param  string    $pString                - Cadena a mapear
     * @return string    $cSupercadena           - Cadena ya mapeada
     */
    function fnCharMap($pString) {
        /**
         * Variables de la funcion
         * @var     array        $vNormal          - Matriz con caracteres validos   
         * @var unknown_type
         */
        /* $vNormal = array(32, 34, 35, 36, 37, 38, 39, 40, 41, 42, 43, 44, 45, 46, 47, 48, 49, 50, 51, 52, 53, 54, 55, 56, 57, 58, 59, 60, 61, 62, 63, 64, 65, 66, 67, 68, 69, 70, 71, 72, 73, 74, 75, 76, 77, 78, 79, 80, 81, 82, 83, 84, 85, 86, 87, 88, 89, 90, 91, 93, 95, 123, 125, 176, 193, 201, 205, 209, 211, 218, 97, 98, 99, 100, 101, 102, 103, 104, 105, 106, 107, 108, 109, 110, 111, 112, 113, 114, 115, 116, 117, 118, 119, 120, 121, 122, 33, 161);
          $cSupercadena = '';
          for ($i = 0; $i < strlen($pString); $i++) {
          $cVari1 = substr($pString, $i, 1);
          $cVari2 = ord($cVari1);
          if (in_array($cVari2, $vNormal)) {
          $cSupercadena .= chr($cVari2);
          } else {
          $cCh64 = $cVari2 + 64;
          if (in_array($cCh64, $vNormal)) {
          $cSupercadena .= chr($cCh64);
          }
          }
          }
          $cSupercadena = str_replace("  ", " ", $cSupercadena);
          $cSupercadena = str_replace("  ", " ", $cSupercadena);
          $cSupercadena = str_replace("  ", " ", $cSupercadena);
          return $cSupercadena; */
        return $pString;
    }
    /**
     * Mapeo caracteres especiales para pdf
     * @param  string    $pString                - Cadena a mapear
     * @return string    $cSupercadena           - Cadena ya mapeada
     */
    function fnCharMapPdf($pString) {
        return utf8_decode($pString);
    }
    /**
     * Mapeo caracteres especiales para pdf
     * @param  string    $pString                - Cadena a mapear
     * @return string    $cSupercadena           - Cadena ya mapeada
     */
    function fnCharMap2($string) {
        $string = utf8_decode($string);
        /* $normal = array(32, 34, 35, 36, 37, 38, 39, 40, 41, 42, 43, 44, 45, 46, 47, 48, 49, 50, 51, 52, 53, 54, 55, 56, 57, 58, 59, 60, 61, 62, 63, 64, 65, 66, 67, 68, 69, 70, 71, 72, 73, 74, 75, 76, 77, 78, 79, 80, 81, 82, 83, 84, 85, 86, 87, 88, 89, 90, 91, 93, 95, 123, 125, 176, 193, 201, 205, 209, 211, 218, 97, 98, 99, 100, 101, 102, 103, 104, 105, 106, 107, 108, 109, 110, 111, 112, 113, 114, 115, 116, 117, 118, 119, 120, 121, 122, 33, 161);
          $arrf = array();
          $longc = strlen($string);
          $supercadena = '';
          for ($i = 0; $i < $longc; $i++) {
          $vari1 = substr($string, $i, 1);
          $vari2 = ord($vari1); //$vari = $var{$i};
          //$arrf[] = $vari2;
          if (in_array($vari2, $normal)) {
          if ($vari2 == 58 || $vari2 == 43 || $vari2 == 34 || $vari2 == 63 || $vari2 == 39) {
          $supercadena .= chr(63);
          }
          $supercadena .= chr($vari2);
          } else {
          $ch64 = $vari2 + 64;
          if (in_array($ch64, $normal)) {
          $supercadena .= chr($ch64);
          }
          }
          } */
        $longc = strlen($string);
        $supercadena = '';
        for ($i = 0; $i < $longc; $i++) {
            $vari1 = substr($string, $i, 1);
            $vari2 = ord($vari1); //$vari = $var{$i};
            //$arrf[] = $vari2;
            if ($vari2 == 58 || $vari2 == 43 || $vari2 == 34 || $vari2 == 63 || $vari2 == 39) {
                $supercadena .= chr(63);
            }
            $supercadena .= $vari1;
        }
        return $supercadena;
        //return $string;
    }
    function fnCharMap3($string) {
        /* $normal = array(32, 34, 35, 36, 37, 38, 39, 40, 41, 42, 43, 44, 45, 46, 47, 48, 49, 50, 51, 52, 53, 54, 55, 56, 57, 58, 59, 60, 61, 62, 63, 64, 65, 66, 67, 68, 69, 70, 71, 72, 73, 74, 75, 76, 77, 78, 79, 80, 81, 82, 83, 84, 85, 86, 87, 88, 89, 90, 91, 93, 95, 123, 125, 176, 193, 201, 205, 209, 211, 218, 97, 98, 99, 100, 101, 102, 103, 104, 105, 106, 107, 108, 109, 110, 111, 112, 113, 114, 115, 116, 117, 118, 119, 120, 121, 122);
          $arrf = array();
          $longc = strlen($string);
          $supercadena = '';
          for ($i = 0; $i < $longc; $i++) {
          $vari1 = substr($string, $i, 1);
          $vari2 = ord($vari1); //$vari = $var{$i};
          if (in_array($vari2, $normal)) {
          if ($vari2 == 172 || $vari2 == 162 || $vari2 == 126 || $vari2 == 124 || $vari2 == 62 || $vari2 == 60) {
          $supercadena .= chr(32);
          } else {
          $supercadena .= $vari1;
          }
          } else {
          $ch64 = $vari2 + 64;
          if (in_array($ch64, $normal)) {
          $supercadena .= chr($ch64);
          }
          }
          } */
        /* $longc = strlen($string);
          $supercadena = '';
          for ($i = 0; $i < $longc; $i++) {
          $vari1 = substr($string, $i, 1);
          $vari2 = ord($vari1); //$vari = $var{$i};
          if ($vari2 == 172 || $vari2 == 162 || $vari2 == 126 || $vari2 == 124 || $vari2 == 62 || $vari2 == 60) {
          $supercadena .= chr(32);
          } else {
          $supercadena .= $vari1;
          }
          }

          return $supercadena; */
        return utf8_decode($string);
    }
    /**
     * 
     * Validacion de ingreso con misma clave para control de licencias
     * @param   string    $pUser                - Id Usuario
     * @param   string    $pAdaptador           - Base de Datos del Usuario
     * @param   string    $pDbGeneral           - Base de Datos General (comunesx)
     * @param   string    $pUsuarios            - Tabla usuarios (sys00010)
     * @param   string    $pLicencia            - Cadena a comparar
     * @return  string    $cError               - Error si lo hay 
     */
    function fnLicencia($pUser, $pAdaptador, $pDbGeneral, $pUsuarios, $pLicencia) {
        /**
         * 
         * Variables de la funcion
         * 
         * @var array  $qSelect    - Senetencia de consulta de licencia de usuario
         * @var array  $xRow       - Cursor de licencia de usuario
         * @var string $cIp        - IP del usuario
         * 
         */
        $cFilePath = "";
        $qSelect = "";
        $xRow = array();
        $cIp = "";
        $cError = "";

        $qSelect = $pDbGeneral->select('usrlicxx')
            ->from($pUsuarios)
            ->where('usridxxx=?', $pUser)
            ->where('usradapt=?', $pAdaptador);
        $xRow = $pDbGeneral->fetchRow($qSelect);
        if ($xRow > 0) {
            if ($xRow['usrlicxx'] != $pLicencia) {
                if ($pUser != "ADMIN") {
                    $cIp = $this->fnIpCheck();
                    $cError .= "Line: " . str_pad(__LINE__, 4, '0', STR_PAD_LEFT);
                    $cError .= " Error: Otro usuario ingreso con su clave desde $cIp~";
                }
            } else {
                if (strlen($pLicencia) == 0) {
                    $cError .= "Line: " . str_pad(__LINE__, 4, '0', STR_PAD_LEFT);
                    $cError .= " Error: No se encontro licencia~";
                }
            }
        } else {
            $cError .= "Line: " . str_pad(__LINE__, 4, '0', STR_PAD_LEFT);
            $cError .= " Error: No se encontro usuario~";
        }
        return $cError;
    }
    /**
     * 
     * Calculo Tasa de Cambio Moneda en Particular
     * @param unknown_type $xFecha
     * @param unknown_type $cMoneda
     */
    function fnBuscarTasaCambio($pFecha, $pMoneda, $pDb) {
        $cTabla = "SIAI0135";
        $nTasa = 0;
        $qSelect = $pDb->select()
            ->from($cTabla)
            ->where("TCAANOXX=?", substr($pFecha, 0, 4))
            ->where("TCASEMXX=?", date("W", mktime(0, 0, 0, substr($pFecha, 5, 2), substr($pFecha, 8, 2), substr($pFecha, 0, 4))))
            ->where("MONIDXXX=?", $pMoneda)
            ->where("REGESTXX=?", "ACTIVO");
        $xResult = $pDb->fetchRow($qSelect);
        if (count($xResult) == 0) { // Valido si es la primera semana del a�o
            if (date("W", mktime(0, 0, 0, substr($pFecha, 5, 2), substr($pFecha, 8, 2), substr($pFecha, 0, 4))) == "1") {
                $qSelect = $pDb->select()
                    ->from($cTabla)
                    ->where("TCAANOXX=?", substr($pFecha, 0, 4) - 1)
                    ->where("MONIDXXX=?", $pMoneda)
                    ->where("REGESTXX=?", "ACTIVO")
                    ->order('TCASEMXX DESC')
                    ->limit(0, 1);
                $xResult = $pDb->fetchRow($qSelect);
                if (count($xResult) > 0) {
                    $nTasa = round($xResult['TCATASAX'], 2);
                }
            }
        } else {
            $nTasa = round($xResult['TCATASAX'], 2);
        }
        return $nTasa;
    }
    /**
     * 
     * Milimetros a Puntos impresion Zend_Pdf
     * @param integer   $pMilimetro           - Milimetros en Hoja
     * @param integer   $pAlto                - Alto en mm de la Hoja
     */
    function fnMPY($pMm, $pAlto) {
        $nEstatico = 2.8346456692913384;
        $pAlto = $pAlto * $nEstatico;
        $nPunto = $pAlto - ($pMm * $nEstatico);
        return $nPunto;
    }
    function fnMPX($pMm) {
        $nEstatico = 2.8346456692913384;
        $nPunto = ($pMm * $nEstatico);
        return $nPunto;
    }
    /**
     * 
     * Mapa de Caracteres para Archivo Plano VUCE
     * @param     string    $pString                     - Cadena a Mapear
     * @return    string    $cSupercadena                - Cadena Mapeada  
     */
    function fnCharmapVuce($pString) {
        $vNormal = array(32, 34, 35, 36, 37, 38, 39, 40, 41, 42, 43, 44, 45);
        $vNormal = array_merge($vNormal, array(46, 47, 48, 49, 50, 51, 52, 53, 54));
        $vNormal = array_merge($vNormal, array(55, 56, 57, 58, 59, 60, 61, 62, 63));
        $vNormal = array_merge($vNormal, array(64, 65, 66, 67, 68, 69, 70, 71, 72));
        $vNormal = array_merge($vNormal, array(73, 74, 75, 76, 77, 78, 79, 80, 81));
        $vNormal = array_merge($vNormal, array(82, 83, 84, 85, 86, 87, 88, 89, 90));
        $vNormal = array_merge($vNormal, array(91, 93, 95, 123, 125, 176, 193, 201));
        $vNormal = array_merge($vNormal, array(205, 209, 211, 218, 97, 98, 99, 100));
        $vNormal = array_merge($vNormal, array(101, 102, 103, 104, 105, 106, 107, 108));
        $vNormal = array_merge($vNormal, array(109, 110, 111, 112, 113, 114, 115, 116));
        $vNormal = array_merge($vNormal, array(117, 118, 119, 120, 121, 122));
        $cSupercadena = '';
        for ($x = 0; $x < strlen($pString); $x++) {
            $vari1 = substr($pString, $x, 1);
            $vari2 = ord($vari1);
            if (in_array($vari2, $vNormal)) {
                if ($vari2 == 172 || $vari2 == 162 || $vari2 == 126 || $vari2 == 124 || $vari2 == 62 || $vari2 == 60) {
                    $cSupercadena .= chr(32);
                } else {
                    $cSupercadena .= $vari1;
                }
            } else {
                $ch64 = $vari2 + 64;
                if (in_array($ch64, $vNormal)) {
                    $cSupercadena .= chr($ch64);
                }
            }
        }
        return $cSupercadena;
    }
    /**
     * 
     * Medicion de cadena pdf
     * @param string $pText Cadena a medir
     * @param string $pFont Fuente que se utiliza
     * @param int $pFontSize Tamano de la fuente
     * @return int
     */
    function fnObtenerAnchoTexto($pText, Zend_Pdf_Resource_Font $pFont, $pFontSize) {
        /**
         * 
         * Variables de la funcion
         * @var string $cDrawingText Conversion a cadena unicode
         * @var array $vCharacters Nuevos caracteres resultantes
         * @var int $nGlyphs caracteres en forma de imagenes para la medicion
         * @var int $nTextWidth medicion en puntos resultante
         */
        $cDrawingText = iconv('', 'UTF-16BE', $pText);
        $vCharacters = array();
        for ($i = 0; $i < strlen($cDrawingText); $i++) {
            $vCharacters[] = (ord($cDrawingText[$i++]) << 8) | ord($cDrawingText[$i]);
        }
        $nGlyphs = $pFont->glyphNumbersForCharacters($vCharacters);
        $nWidths = $pFont->widthsForGlyphs($nGlyphs);
        $nTextWidth = (array_sum($nWidths) / $pFont->getUnitsPerEm()) * $pFontSize;
        return $nTextWidth;
    }
    /**
     * 
     * Mil mas cercano para declaraciones Importacion 
     * @param float $pNumber Cifra a Procesar
     */
    function fnMilCercano($pNumber) {
        $vTodo = explode(".", $pNumber);
        $nDecimal = 0;
        if (count($vTodo) == 2) {
            $nDecimal = 0 + $vTodo[1];
        }

        $cNumber = $vTodo[0];
        //$cNumber = round($cNumber);
        $cRet = $cNumber;
        $sube = 0;
        $ult3 = 0 + (substr($cNumber, strlen($cNumber) - 3, 3));
        $prim = substr($cNumber, 0, strlen($cNumber) - 3);

        //echo "ult3 es: ".$ult3."<br>";
        //echo "prim es: ".$prim."<br>";


        if (strlen($cNumber) > 3) {
            if ($ult3 >= 500 || ($ult3 == 500 && round($nDecimal, 2) > 0)) {// && round($decimal,2) > 0)
                $cRet = ($prim + 1) . "000";
            } else {
                $cRet = $prim . "000";
            }
        } else {
            if ($cNumber > 0 && $cNumber >= 500) {
                $cRet = "1000";
            } else {
                $cRet = "0";
            }
        }
        return $cRet;
    }
    /**
     * 
     * Formato Fecha
     * @param date $pFecha
     * @return string
     */
    function fnFormatoFecha($pFecha) {
        $cFector = "";
        $cFmes = "";
        if ($pFecha == '') {
            $cFector = '';
        } else {
            $cFano = substr($pFecha, 0, 4);
            $cFdia = substr($pFecha, 8, 2);
            $cFmesAntes = substr($pFecha, 5, 2);
            switch ($cFmesAntes) {
                case'01':
                    $cFmes = "Enero";
                    break;
                case'02':
                    $cFmes = "Febrero";
                    break;
                case'03':
                    $cFmes = "Marzo";
                    break;
                case'04':
                    $cFmes = "Abril";
                    break;
                case'05':
                    $cFmes = "Mayo";
                    break;
                case'06':
                    $cFmes = "Junio";
                    break;
                case'07':
                    $cFmes = "Julio";
                    break;
                case'08':
                    $cFmes = "Agosto";
                    break;
                case'09':
                    $cFmes = "Septiembre";
                    break;
                case'10':
                    $cFmes = "Octubre";
                    break;
                case'11':
                    $cFmes = "Noviembre";
                    break;
                case'12':
                    $cFmes = "Diciembre";
                    break;
            }

            $cFector = $cFdia . " dias del mes de " . $cFmes . " de " . $cFano;
        }
        return ($cFector);
    }
    /**
     * Ordenamiento de arreglo de datos para formulario de inicio
     * @param array $pArr          - Arreglo a procesar
     * @param string $pField       - Ordenar por este campo
     * @param string $pSortType    - Tipo de orden, numerico o alfanumerico
     * @return array
     */
    function fnSortArrayByField($pArr, $pField, $pSortType) {
        $i = 0;
        $grouped_arr = array();
        foreach ($pArr as $value) {
            $is_contiguous = true;
            if (!empty($grouped_arr)) {
                $last_value = end($grouped_arr[$i]);
            }
            if ($is_contiguous)
                $grouped_arr[$i][] = $value;
            else
                $grouped_arr[++$i][] = $value;
        }
        $code = '';
        switch ($pSortType) {
            case "ASC_AZ":
                $code .= 'return strcasecmp($a["' . $pField . '"], $b["' . $pField . '"]);';
                break;
            case "DESC_AZ":
                $code .= 'return (-1*strcasecmp($a["' . $pField . '"], $b["' . $pField . '"]));';
                break;
            case "ASC_NUM":
                $code .= 'return ($a["' . $pField . '"] - $b["' . $pField . '"]);';
                break;
            case "DESC_NUM":
                $code .= 'return ($b["' . $pField . '"] - $a["' . $pField . '"]);';
                break;
        }
        $compare = create_function('$a, $b', $code);
        foreach ($grouped_arr as $grouped_arr_key => $grouped_arr_value)
            usort($grouped_arr[$grouped_arr_key], $compare);
        $pArr = array();
        foreach ($grouped_arr as $grouped_arr_key => $grouped_arr_value)
            foreach ($grouped_arr[$grouped_arr_key] as $grouped_arr_arr_key => $grouped_arr_arr_value)
                $pArr[] = $grouped_arr[$grouped_arr_key][$grouped_arr_arr_key];
        return $pArr;
    }
    /**
     * 
     * Funcion PDF Medir cadenas
     * @param unknown_type $xDes
     * @param unknown_type $xAncho
     * @param unknown_type $fuente
     * @param unknown_type $tamano
     */
    function fnPdfWords($xDes, $xAncho, $fuente, $tamano) {
        $pdf2 = new FPDF('P', 'mm', "letter");
        $pdf2->AddFont("$fuente", '', "$fuente.php");
        $pdf2->SetFont("$fuente", '', $tamano);
        $linea = 0;
        $supercad = '';
        $itdes = $xDes;
        $desr = str_replace("  ", " ", $itdes);
        $ardes = explode(" ", $desr);
        $arrfi2 = array();
        $arrfi2[0] = '';
        $idf = 0;
        $esp = " ";
        $rango = count($ardes);
        $cadena = '';
        for ($w = 0; $w < $rango; $w++) {
            $desc = $ardes[$w] . $esp;
            $sumc = $cadena . $desc;
            $longi = $pdf2->GetStringWidth($sumc);
            if ($longi > $xAncho) {
                $idf = $idf + 1;
                $cadena = $desc;
                $arrfi2[$idf] = $desc;
            } else {
                $cadena = $cadena . $desc;
                $arrfi2[$idf] = $arrfi2[$idf] . $desc;
            }
            //$arrfi2[$idf] = $arrfi2[$idf] . $desc;
        }
        $lona = count($arrfi2);
        for ($loc = 0; $loc < $lona; $loc++) {
            $cadena = trim($arrfi2[$loc]);
            $longi = $pdf2->GetStringWidth($cadena);
            $arcad = explode(" ", $cadena);
            $lar = count($arcad) - 1;
            $arranque = 0;
            while ($longi < $xAncho) {
                if ($arranque > $lar - 1) {
                    $arranque = 0;
                }
                $arcad[$arranque] = $arcad[$arranque] . " ";
                $cadena = $cadena . " ";
                $longi = $pdf2->GetStringWidth($cadena);
                $arranque = $arranque + 1;
            }
            $cadena = '';
            $lonim = $lar + 1;
            for ($caf = 0; $caf < $lonim; $caf++) {
                $cadena = $cadena . $arcad[$caf] . " ";
            }
            if ($loc == ($lona - 1)) {
                $cadena = str_replace("     ", " ", $cadena);
                $cadena = str_replace("    ", " ", $cadena);
                $cadena = str_replace("   ", " ", $cadena);
                $cadena = str_replace("  ", " ", $cadena);
            }
            $cadena = str_replace("  ", " ", $cadena);
            $cadena = str_replace("  ", " ", $cadena);
            $arrfi2[$loc] = $cadena;
            $supercad .= $cadena . "~";
        }
        return $supercad;
    }
    /**
     * 
     * @param float $wcuantia   -   Monto
     * @param string $moneda    -   Moneda
     * @return string
     */
    function fnCifra($wcuantia, $moneda) {
        $mone1 = '';
        $mone2 = '';
        switch ($moneda) {
            case 'PESO':
                $mone1 = 'PESO';
                $mone2 = 'PESOS';
                break;
            case 'DOLAR':
                $mone1 = 'DOLAR';
                $mone2 = 'DOLARES';
                break;
            case 'EUR':
                $mone1 = 'EURO';
                $mone2 = 'EUROS';
                break;
        }
        $warreglo_1 = array(" UN",
            " DOS",
            " TRES",
            " CUATRO",
            " CINCO",
            " SEIS",
            " SIETE",
            " OCHO",
            " NUEVE",
            " DIEZ",
            " ONCE",
            " DOCE",
            " TRECE",
            " CATORCE",
            " QUINCE",
            " DIECISEIS",
            " DIECISIETE",
            " DIECIOCHO",
            " DIECINUEVE",
            " VEINTE",
            " VEINTIUN",
            " VEINTIDOS",
            " VEINTITRES",
            " VEINTICUATRO",
            " VEINTICINCO",
            " VEINTISEIS",
            " VEINTISIETE",
            " VEINTIOCHO",
            " VEINTINUEVE",
            " TREINTA",
            " TREINTA Y UN",
            " TREINTA Y DOS",
            " TREINTA Y TRES",
            " TREINTA Y CUATRO",
            " TREINTA Y CINCO",
            " TREINTA Y SEIS",
            " TREINTA Y SIETE",
            " TREINTA Y OCHO",
            " TREINTA Y NUEVE",
            " CUARENTA",
            " CUARENTA Y UN",
            " CUARENTA Y DOS",
            " CUARENTA Y TRES",
            " CUARENTA Y CUATRO",
            " CUARENTA Y CINCO",
            " CUARENTA Y SEIS",
            " CUARENTA Y SIETE",
            " CUARENTA Y OCHO",
            " CUARENTA Y NUEVE",
            " CINCUENTA",
            " CINCUENTA Y UN",
            " CINCUENTA Y DOS",
            " CINCUENTA Y TRES",
            " CINCUENTA Y CUATRO",
            " CINCUENTA Y CINCO",
            " CINCUENTA Y SEIS",
            " CINCUENTA Y SIETE",
            " CINCUENTA Y OCHO",
            " CINCUENTA Y NUEVE",
            " SESENTA",
            " SESENTA Y UN",
            " SESENTA Y DOS",
            " SESENTA Y TRES",
            " SESENTA Y CUATRO",
            " SESENTA Y CINCO",
            " SESENTA Y SEIS",
            " SESENTA Y SIETE",
            " SESENTA Y OCHO",
            " SESENTA Y NUEVE",
            " SETENTA",
            " SETENTA Y UN",
            " SETENTA Y DOS",
            " SETENTA Y TRES",
            " SETENTA Y CUATRO",
            " SETENTA Y CINCO",
            " SETENTA Y SEIS",
            " SETENTA Y SIETE",
            " SETENTA Y OCHO",
            " SETENTA Y NUEVE",
            " OCHENTA",
            " OCHENTA Y UN",
            " OCHENTA Y DOS",
            " OCHENTA Y TRES",
            " OCHENTA Y CUATRO",
            " OCHENTA Y CINCO",
            " OCHENTA Y SEIS",
            " OCHENTA Y SIETE",
            " OCHENTA Y OCHO",
            " OCHENTA Y NUEVE",
            " NOVENTA",
            " NOVENTA Y UN",
            " NOVENTA Y DOS",
            " NOVENTA Y TRES",
            " NOVENTA Y CUATRO",
            " NOVENTA Y CINCO",
            " NOVENTA Y SEIS",
            " NOVENTA Y SIETE",
            " NOVENTA Y OCHO",
            " NOVENTA Y NUEVE");

        $warreglo_2 = array(" CIENTO",
            " DOSCIENTOS",
            " TRESCIENTOS",
            " CUATROCIENTOS",
            " QUINIENTOS",
            " SEISCIENTOS",
            " SETECIENTOS",
            " OCHOCIENTOS",
            " NOVECIENTOS");

        $wletras = '';
        $wmilmill = '';
        $wmillon = '';
        $wmil = '';
        $wcien = '';
        $wcent = '';
        $wnum = '';
        $divide = explode('.', $wcuantia);
        if (count($divide) == 1) {
            $divide[1] = '0';
        }
        $longi = strlen($divide[0]);
        if (strlen($divide[1]) >= 2) {
            if (substr($divide[1], 0, 1) == '0') {
                $divide[1] = substr($divide[1], 1, 1);
            }
        } else {
            if (strlen($divide[1]) == 1) {
                $divide[1] = $divide[1] . '0';
            }
        }

        $wlinea = '';
        $resta = 12 - $longi;
        $cdain = '';
        for ($i = 0; $i < $resta; $i++) {
            $cdain = $cdain . " ";
        }

        $wnum = $cdain . $divide[0] . '.' . $divide[1];
        $wcent = substr($wnum, 13, 2);
        $wcien = substr($wnum, 9, 3);
        $wmil = substr($wnum, 6, 3);
        $wmillon = substr($wnum, 3, 3);
        $wmilmill = substr($wnum, 0, 3);
        if (intval($wmilmill) > 0) {
            if (intval($wmilmill) == 100) {
                $wletras = " CIEN MIL";
                $wlinea = $wlinea . $wletras;
            } else {
                if (intval($wmilmill) == 1) {
                    $wletras = " MIL";
                    $wlinea = $wlinea . $wletras;
                } else {
                    $wi = intval(substr($wmilmill, 0, 1));
                    if ($wi > 0) {
                        $idx = $wi - 1;
                        $wletras = $warreglo_2[$idx];
                        $wlinea = $wlinea . $wletras;
                    }
                    $wi = intval(substr($wmilmill, 1, 2));
                    if ($wi > 0) {
                        $idx = $wi - 1;
                        $wletras = $warreglo_1[$idx];
                        $wlinea = $wlinea . $wletras;
                    }
                    $wletras = " MIL";
                    $wlinea = $wlinea . $wletras;
                }
            }
        }

        if (intval($wmillon) > 0) {
            if (intval($wmillon) == 100) {
                $wletras = " CIEN MILLONES";
                $wlinea = $wlinea . $wletras;
            } else {
                if (intval($wmillon) == 1) {
                    $wletras = " UN MILLON";
                    $wlinea = $wlinea . $wletras;
                } else {
                    $wi = intval(substr($wmillon, 0, 1));
                    if ($wi > 0) {
                        $idx = $wi - 1;
                        $wletras = $warreglo_2[$idx];
                        $wlinea = $wlinea . $wletras;
                    }
                    $wi = intval(substr($wmillon, 1, 2));
                    if ($wi > 0) {
                        $idx = $wi - 1;
                        $wletras = $warreglo_1[$idx];
                        $wlinea = $wlinea . $wletras;
                    }
                    $wletras = " MILLONES";
                    $wlinea = $wlinea . $wletras;
                }
            }
        } else {
            if (intval($wmilmill) > 0) {
                $wletras = " MILLONES";
                $wlinea = $wlinea . $wletras;
            }
        }

        if (intval($wmil) > 0) {
            if (intval($wmil) == 100) {
                $wletras = " CIEN MIL";
                $wlinea = $wlinea . $wletras;
            } else {
                if (intval($wmil) == 1) {
                    $wletras = " MIL";
                    $wlinea = $wlinea . $wletras;
                } else {
                    $wi = intval(substr($wmil, 0, 1));
                    if ($wi > 0) {
                        $idx = $wi - 1;
                        $wletras = $warreglo_2[$idx];
                        $wlinea = $wlinea . $wletras;
                    }
                    $wi = intval(substr($wmil, 1, 2));
                    if ($wi > 0) {
                        $idx = $wi - 1;
                        $wletras = $warreglo_1[$idx];
                        $wlinea = $wlinea . $wletras;
                    }
                    $wletras = " MIL";
                    $wlinea = $wlinea . $wletras;
                }
            }
        }

        if (intval($wcien) == 100) {
            $wletras = " CIEN";
            $wlinea = $wlinea . $wletras;
        } else {
            if (intval($wcien) == 0) {
                if ($wmil == 0 && $wmillon == 0 && $wmilmill == 0) {
                    $wletras = " CERO";
                    $wlinea = $wlinea . $wletras;
                }
            } else {
                $wi = substr($wcien, 0, 1);
                if ($wi > 0) {
                    $idx = $wi - 1;
                    $wletras = $warreglo_2[$idx];
                    //alert(wletras);
                    $wlinea = $wlinea . $wletras;
                }
                $wi = intval(substr($wcien, 1, 2));
                if ($wi > 0) {
                    $idx = $wi - 1;
                    $wletras = $warreglo_1[$idx];
                    $wlinea = $wlinea . $wletras;
                }
            }
        }

        if (intval($wcien) == 1) {
            $wletras = " $mone1 CON";
            $wlinea = $wlinea . $wletras;
        } else {
            if ((intval($wcien) == 0 && intval($wmil) == 0 ) && (intval($wmillon) > 0 || intval($wmilmill) > 0)) {
                $wletras = " $mone2 CON";
                $wlinea = $wlinea . $wletras;
            } else {
                $wletras = " $mone2 CON";
                $wlinea = $wlinea . $wletras;
            }
        }

        if (intval($wcent) == 1) {
            $wletras = " UN CENTAVO";
            // wletras = ' 1/100';
        } else {
            if ($wcent > 1) {
                $idx = $wcent - 1;
                $wletras = $warreglo_1[$idx];
                $wletras = $wletras . " CENTAVOS";
                //wletras = ' '+wcent+'/100'
            } else {
                $wletras = " CERO CENTAVOS";
                //wletras = ' 00/100';
            }
        }
        $wlinea = $wlinea . $wletras;
        //alert(wlinea);
        return $wlinea;
        //fld.value=wlinea;
    }
    /**
     * Retornar letra excel desde numero
     * @param integer $pNumero      - Numero de column
     * @return string
     */
    function fnNum2alpha($pNumero) {
        $cAlpha = "";
        for ($cAlpha = ""; $pNumero >= 0; $pNumero = intval($pNumero / 26) - 1) {
            $cAlpha = chr($pNumero % 26 + 0x41) . $cAlpha;
        }
        return $cAlpha;
    }
    /**
     * 
     * @param string $hi      - Hora Inicial
     * @param string $hf      - Hora Final
     * @return string
     */
    function fnSegundos($hi, $hf) {
        $hh1 = substr($hi, 0, 2);
        $mm1 = substr($hi, 3, 2);
        $ss1 = substr($hi, 6, 2);

        $hh2 = substr($hf, 0, 2);
        $mm2 = substr($hf, 3, 2);
        $ss2 = substr($hf, 6, 2);

        $hh1f = abs($hh1) * 3600;
        $mm1f = abs($mm1) * 60;
        $ss1f = $hh1f + $mm1f + abs($ss1);

        $hh2f = abs($hh2) * 3600;
        $mm2f = abs($mm2) * 60;
        $ss2f = $hh2f + $mm2f + abs($ss2);

        $tss = $ss2f - $ss1f;
        return $tss;
    }
    /* creates a compressed zip file */
    function fnZip($files = array(), $destination = '', $overwrite = false, $distill_subdirectories = true) {
        //if the zip file already exists and overwrite is false, return false
        if (file_exists($destination) && !$overwrite) {
            return false;
        }
        //vars
        $valid_files = array();
        //if files were passed in...
        if (is_array($files)) {
            //cycle through each file
            foreach ($files as $file) {
                //make sure the file exists
                if (file_exists($file)) {
                    $valid_files[] = $file;
                }
            }
        }
        //if we have good files...
        if (count($valid_files)) {
            /* $cTxt = $_SERVER['DOCUMENT_ROOT'].'/xmls/prozip.txt';
              if (is_file($cTxt)) {
              unlink($cTxt);
              }
              $fp = fopen($cTxt, 'a+');
              fwrite($fp, count($valid_files));
              fclose($fp); */
            //create the archive
            $zip = new ZipArchive();
            /* if ($zip->open($destination, $overwrite ? ZIPARCHIVE::OVERWRITE : ZIPARCHIVE::CREATE) != true) {
              return false;
              } */
            /* if ($overwrite) {
              if ($zip->open($destination, ZipArchive::OVERWRITE) != true) {
              return false;
              }
              } else {
              if ($zip->open($destination, ZipArchive::CREATE) != true) {
              return false;
              }
              } */
            $zip->open($destination, ZipArchive::CREATE);
            //add the files
            /* foreach ($valid_files as $file) {
              $zip->addFile($file, $file);
              } */
            foreach ($valid_files as $file) {
                if ($distill_subdirectories) {
                    $zip->addFile($file, basename($file));
                } else {
                    $zip->addFile($file, $file);
                }
            }
            //debug
            //echo 'The zip archive contains ',$zip->numFiles,' files with a status of ',$zip->status;
            //close the zip -- done!
            $zip->close();
            //check to make sure the file exists
            return file_exists($destination);
        } else {
            return false;
        }
    }
    /**
     * 
     * @param string $cadena        -   Texto a Justificar
     * @param int $ancho            -   Ancho de linea
     * @param string $fuente        -   Fuente empleada
     * @param int $tamano           -   Tamano de la Fuente
     * @return string
     */
    function fnPdfJustify($cadena, $ancho, $fuente, $tamano) {
        $pdf2 = new FPDF('P', 'mm', 'Letter');
        $pdf2->AddFont("$fuente", '', "$fuente.php");
        $pdf2->SetFont("$fuente", '', $tamano);
        $arranque = 0;
        $longi = $pdf2->GetStringWidth($cadena);
        //f_Mensaje("","inicia: ",$longi);
        $arcad = explode(" ", $cadena);
        $lar = count($arcad) - 1;
        while ($longi < $ancho) {
            if ($arranque > $lar - 1) {
                $arranque = 0;
            }
            $arcad[$arranque] = $arcad[$arranque] . " ";
            $cadena = $cadena . " ";
            $longi = $pdf2->GetStringWidth($cadena);
            $arranque = $arranque + 1;
        }
        $cadena = '';
        $lonim = $lar + 1;
        for ($caf = 0; $caf < $lonim; $caf++) {
            $cadena = $cadena . $arcad[$caf] . " ";
        }
        //f_Mensaje("","retorna: ",$pdf2->GetStringWidth($cadena));
        return $cadena;
    }
    /**
     * Subcadenas comunes entre dos cadenas
     * @param string $aar1      -   Cadena 1
     * @param string $aar2      -   Cadena 2
     * @return string
     */
    function fnComunes($aar1, $aar2) {
        $ar1 = explode("~", $aar1);
        $ar2 = explode("~", $aar2);
        $arfin = array();
        $idx = 0;
        $comun = '';
        $conteo = 0;
        for ($x = 0; $x < count($ar2); $x++) {
            if (strlen($ar2[$x]) > 0) {
                for ($n = 0; $n < count($ar1); $n++) {
                    if (strlen($ar1[$n]) > 0) {
                        if ($ar1[$n] == $ar2[$x]) {
                            $z = $x + 1;
                            $zz = $n + 1;
                            $comun .= " " . $ar2[$x];
                            $conteo++;
                            while ($ar2[$z] == $ar1[$zz]) {
                                $conteo++;
                                $comun .= " " . $ar1[$zz];
                                $z++;
                                $zz++;
                                $x++;
                            }
                            if ($conteo >= 3) {
                                $arfin[$idx] = $comun;
                                $idx++;
                            }
                            $conteo = 0;
                            $comun = '';
                        }
                    }
                }
            }
        }
        return $arfin;
    }
    /**
     * 
     * @param string $pString      - Cadena a limpiar caracteres
     * @return string
     */
    function fnLatinString($pString) {
        $cCadena = str_replace(array(chr(27), chr(13), chr(9), chr(10)), "", $pString);
        $cCadena = str_replace("Ã³", "ó", $cCadena);
        $cCadena = str_replace("Ã¡", "á", $cCadena);
        $cCadena = str_replace("Ã±", "ñ", $cCadena);
        $cCadena = str_replace("Ã©", "é", $cCadena);
        $cCadena = str_replace("Ãº", "ú", $cCadena);
        $cCadena = str_replace("Ã", "í", $cCadena);
        $cCadena = str_replace("Ã‰", "É", $cCadena);
        $cCadena = str_replace("Ã“", "Ó", $cCadena);
        $cCadena = str_replace("Ã‘", "Ñ", $cCadena);
        $cCadena = str_replace("Ãš", "Ú", $cCadena);
        $cCadena = str_replace("Ã", "Á", $cCadena);
        $cCadena = str_replace("Ã", "Í", $cCadena);
        return $cCadena;
    }
    /**
     * Validacion de Solo numeros
     * @param string $pString       - Cadena a Validar
     * @return string
     */
    function fnSoloNumeros($pString) {
        $aNum = array("0", "1", "2", "3", "4", "5", "6", "7", "8", "9");
        $cadf = "";
        for ($xl = 0; $xl < strlen($pString); $xl++) {
            if (in_array(substr($pString, $xl, 1), $aNum, true)) {
                $cadf .= substr($pString, $xl, 1);
            }
        }
        return $cadf;
    }
    /**
     * Licencia chart director
     * @return string
     */
    function fnChartLicense() {
        return "SJ2QQ6SPA7HTCHKU526B8FC7";
    }
    /**
     * Conectar FTP
     * @param string $servidor          -  Servidor
     * @param string $puerto            -  Puerto
     * @param string $usuario           -  Usuario
     * @param string $password          -  Password
     * @param string $modo              -  Modo de conexion
     * @param type $archivo_local       -  Archivo Local
     * @param string $archivo_remoto    -  Archivo Remoto
     */
    function fnConectarFTP($servidor, $puerto, $usuario, $password, $modo, $archivo_local, $archivo_remoto) {
        $cRetorno = 'false';
        try {
            $id_ftp = ftp_connect($servidor, $puerto); //Obtiene un manejador del Servidor FTP
            ftp_login($id_ftp, $usuario, $password); //Se loguea al Servidor FTP
            ftp_pasv($id_ftp, $modo); //Establece el modo de conexion
            $cFtp = ftp_put($id_ftp, $archivo_remoto, $archivo_local, FTP_BINARY);
            ftp_quit($id_ftp);
            $cRetorno = "false";
            if ($cFtp) {
                $cRetorno = "true";
            }
        } catch (Exception $e) {
            $cRetorno = $e->getMessage();
        }
        return $cRetorno;
    }
    function fnNumeroAleatorio($pL = 4) {
        $a = '';
        for ($i = 0; $i < $pL; $i++) {
            $a .= mt_rand(0, 9);
        }
        return $a;
    }
    function fnQuitarBloques($pStr, $pMod) {
        $nBlo1 = 0;
        $nBlo2 = 0;
        $cCar1 = '!_';
        $cCar2 = '_!';
        $pStr2 = $pStr;
        switch ($pMod) {
            case 'importaciones':
                break;
            case 'vuce':
                $cCar1 = '^_';
                $cCar2 = '_^';
                break;
        }
        $nBlo1 = substr_count($pStr, $cCar1);
        $nBlo2 = substr_count($pStr, $cCar2);
        if ($nBlo1 > 0 && $nBlo2 > 0 && $nBlo1 == $nBlo2) {
            $pStr2 = '';
            $vBlo = explode("{$cCar1}", $pStr);
            foreach ($vBlo as $zB) {
                if (substr_count($zB, $cCar2) <= 0) {
                    $pStr2 .= $zB;
                } else {
                    $vBlo2 = explode("{$cCar2}", $zB);
                    if (count($vBlo2) == 2) {
                        $pStr2 .= $vBlo2[1];
                    }
                }
            }
            $pStr2 = str_replace(array("!_", "_!", "^_", "_^"), "", $pStr2);
        }
        $pStr2 = str_replace(array("!_", "_!", "^_", "_^"), "", $pStr2);
        return $pStr2;
        /* echo $pStr2;
          echo "<br>";
          $pStr = str_replace($cCar1, "<b>", $pStr);
          $pStr = str_replace($cCar2, "</b>", $pStr);
          echo $pStr; */
    }
    function fnDiffT($old, $new) {
        $matrix = array();
        $maxlen = 0;
        foreach ($old as $oindex => $ovalue) {
            $nkeys = array_keys($new, $ovalue);
            foreach ($nkeys as $nindex) {
                $matrix[$oindex][$nindex] = isset($matrix[$oindex - 1][$nindex - 1]) ?
                    $matrix[$oindex - 1][$nindex - 1] + 1 : 1;
                if ($matrix[$oindex][$nindex] > $maxlen) {
                    $maxlen = $matrix[$oindex][$nindex];
                    $omax = $oindex + 1 - $maxlen;
                    $nmax = $nindex + 1 - $maxlen;
                }
            }
        }
        if ($maxlen == 0) {
            return array(array('d' => $old, 'i' => $new));
        }
        return array_merge($this->fnDiffT(array_slice($old, 0, $omax), array_slice($new, 0, $nmax)), array_slice($new, $nmax, $maxlen), $this->fnDiffT(array_slice($old, $omax + $maxlen), array_slice($new, $nmax + $maxlen)));
    }
    function fnDiferenciasTexto($pOri, $pFin) {
        $ret = '';
        $diff = $this->fnDiffT(preg_split("/[\s]+/", $pOri), preg_split("/[\s]+/", $pFin));
        foreach ($diff as $k) {
            if (is_array($k)) {
                $ret .= (!empty($k['d']) ? "<del><font color='red'>" . implode(' ', $k['d']) . "</font></del> " : '') . (!empty($k['i']) ? "<ins><font color='blue'>" . implode(' ', $k['i']) . "</font></ins> " : '');
            } else {
                $ret .= $k . ' ';
            }
        }
        return $ret;
    }
    function fnDiferenciasTextoNO($pOri, $pFin) {
        $ret = '';
        /* $diff = $this->fnDiffT(preg_split("/[\s]+/", $pOri), preg_split("/[\s]+/", $pFin));
          foreach ($diff as $k) {
          if (is_array($k)) {
          $ret .= (!empty($k['d']) ? "<del><font color='red'>" . implode(' ', $k['d']) . "</font></del> " : '') . (!empty($k['i']) ? "<ins><font color='blue'>" . implode(' ', $k['i']) . "</font></ins> " : '');
          } else {
          $ret .= $k . ' ';
          }
          } */
        return $ret;
    }
    function fnValidarFecha($date, $format = 'Y-m-d') {
        $d = DateTime::createFromFormat($format, $date);
        // The Y ( 4 digits year ) returns TRUE for any integer with any number of digits so changing the comparison from == to === fixes the issue.
        return $d && $d->format($format) === $date;
    }
    //Fin Clase
}