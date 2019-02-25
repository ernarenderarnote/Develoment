@extends("admin.layouts.admin")

@section("title")
    @if (!empty($subtitle))
        {{ $subtitle }} - 
    @endif
    {{ $title }}
@stop

@section("bodyClasses", "page")
    
@section('styles')
<style>
    body .glyphicon {
        position: relative;
        top: 1px;
        display: inline-block;
        font-family: 'Glyphicons Halflings';
        font-style: normal;
        font-weight: normal;
        line-height: 1;
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
      }
</style>
<link href="{{ url('/vendor/beanstalk_console/css/customer.css') }}" rel="stylesheet" />
@append

@section("content")    
<div class="row">
    @include('queue-monitor::status-page')
    <div class="col-xs-12">
    
        <div class="box box-primary">
            <div class="box-body pos-r">
                <?php if (!empty($servers)): ?>
                    <div class="navbar navbar-static-top navbar-default" role="navigation">
                        <div class="container">
                            <div class="navbar-header">
                                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                                    <span class="sr-only">Toggle navigation</span>
                                    <span class="icon-bar"></span>
                                    <span class="icon-bar"></span>
                                    <span class="icon-bar"></span>
                                </button>
                                <a class="navbar-brand" href="./?">Beanstalk console</a>
                            </div>
                            <div class="collapse navbar-collapse">
                                <ul class="nav navbar-nav">
        
                                    <?php if ($server && $tube): ?>
                                        <li class="dropdown">
                                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                                <?php echo $server ?> <span class="caret"></span>
                                            </a>
                                            <ul class="dropdown-menu">
                                                <li><a href="./?">All servers</a></li>
                                                <?php foreach (array_diff($servers, array($server)) as $serverItem): ?>
                                                    <li><a href="./?server=<?php echo $serverItem ?>"><?php echo $serverItem ?></a></li>
                                                <?php endforeach ?>
                                            </ul>
                                        </li>
                                        <li class="dropdown">
                                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                                <?php echo $tube ?> <span class="caret"></span>
                                            </a>
                                            <ul class="dropdown-menu">
                                                <li><a href="./?server=<?php echo $server ?>">All tubes</a></li>
                                                <?php foreach (array_diff($tubes, array($tube)) as $tubeItem): ?>
                                                    <li><a href="./?server=<?php echo $server ?>&tube=<?php echo $tubeItem ?>"><?php echo $tubeItem ?></a></li>
                                                <?php endforeach ?>
                                            </ul>
                                        </li>
                                    <?php elseif ($server): ?>
                                        <li class="dropdown">
                                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                                <?php echo $server ?> <span class="caret"></span>
                                            </a>
                                            <ul class="dropdown-menu">
                                                <li><a href="./?">All servers</a></li>
                                                <?php foreach (array_diff($servers, array($server)) as $serverItem): ?>
                                                    <li><a href="./?server=<?php echo $serverItem ?>"><?php echo $serverItem ?></a></li>
                                                <?php endforeach ?>
                                            </ul>
                                        </li>
                                        <li class="dropdown">
                                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                                All tubes <span class="caret"></span>
                                            </a>
                                            <ul class="dropdown-menu">
                                                <?php
                                                if (isset($tubes) && is_array($tubes)) {
                                                    foreach ($tubes as $tubeItem) {
                                                        ?>
                                                        <li><a href="./?server=<?php echo $server ?>&tube=<?php echo $tubeItem ?>"><?php echo $tubeItem ?></a></li>
                                                    <?php
                                                    }
                                                }
                                                ?>
                                            </ul>
                                        </li>
                                    <?php else:
                                        ?>
                                        <li class="dropdown">
                                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                                All servers <span class="caret"></span>
                                            </a>
                                            <ul class="dropdown-menu">
                                                <?php foreach ($servers as $serverItem): ?>
                                                    <li><a href="./?server=<?php echo $serverItem ?>"><?php echo $serverItem ?></a></li>
                                                <?php endforeach ?>
                                            </ul>
                                        </li>
                                    <?php endif ?>
                                </ul>
        
                                <ul class="nav navbar-nav navbar-right">
                                    <li class="dropdown">
                                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">Toolbox <span class="caret"></span></a>
                                        <ul class="dropdown-menu">
                                            <?php if (!isset($_tplPage) && !$server) { ?>
                                                <li><a href="#filterServer" role="button" data-toggle="modal">Filter columns</a></li>
                                                <?php
                                            } elseif (!isset($_tplPage) && $server) {
                                                ?>
                                                <li><a href="#filter" role="button" data-toggle="modal">Filter columns</a></li>
                                                <?php
                                            }
                                            if ($server && !$tube) {
                                                ?>
                                                <li><a href="#clear-tubes" role="button" data-toggle="modal">Clear multiple tubes</a></li>
                                            <?php } ?>
                                            <li><a href="./?action=manageSamples" role="button">Manage samples</a></li>
                                            <li class="divider"></li>
                                            <li><a href="#settings" role="button" data-toggle="modal">Edit settings</a></li>
                                        </ul>
                                    </li>
                                    <li class="dropdown">
                                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">Links <span class="caret"></span></a>
                                        <ul class="dropdown-menu">
                                            <li><a href="https://github.com/kr/beanstalkd">Beanstalk (github)</a></li>
                                            <li><a href="https://github.com/kr/beanstalkd/blob/master/doc/protocol.md">Protocol Specification</a></li>
                                            <li class="divider"></li>
                                            <li><a href="https://github.com/ptrofimov/beanstalk_console">Beanstalk console (github)</a></li>
                                        </ul>
                                    </li>
                                        <?php if (@$config['auth']['enabled']) { ?>
                                        <li class="dropdown">
                                            <a target="_blank" href="./?logout=true">logout <span class="glyphicon glyphicon-log-out" aria-hidden="true"></span></a>
                                        </li>
                                    <?php } ?>
                                    <?php if ($server && !$tube) { ?>
                                        <li>
                                            <button type="button" id="autoRefresh" class="btn btn-default btn-small">
                                                <span class="glyphicon glyphicon-refresh"></span>
                                            </button>
                                        </li>
                                    <?php } else if (!$tube) { ?>
                                        <li>
                                            <button type="button" id="autoRefreshSummary" class="btn btn-default btn-small">
                                                <span class="glyphicon glyphicon-refresh"></span>
                                            </button>
                                        </li>
                                    <?php } ?>
                                </ul>
                                <?php if (isset($server, $tube) && $server && $tube) { ?>
                                    <form  class="navbar-form navbar-right" style="margin-top:5px;margin-bottom:0px;" role="search" action="" method="get">
                                        <input type="hidden" name="server" value="<?php echo $server; ?>"/>
                                        <input type="hidden" name="tube" value="<?php echo $tube; ?>"/>
                                        <input type="hidden" name="state" value="<?php echo $state; ?>"/>
                                        <input type="hidden" name="action" value="search"/>
                                        <input type="hidden" name="limit" value="<?php echo empty($_COOKIE['searchResultLimit']) ? 25 : $_COOKIE['searchResultLimit']; ?>"/>
                                        <div class="form-group">
                                            <input type="text" class="form-control input-sm search-query" name="searchStr" placeholder="Search this tube">
                                        </div>
                                    </form>
                                <?php } ?>
                            </div>
                            <!--/.nav-collapse -->
                        </div>
                    </div>
        
                    <div class="col-xs-12">
                <?php endif /* if (!empty($servers)): */ ?>
        
                    <?php if (!empty($consoleErrors)): ?>
                        <?php foreach ($consoleErrors as $item): ?>
                            <p class="alert alert-error"><span class="label label-important">Error</span> <?php echo $item ?></p>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <?php if (isset($_tplPage)): ?>
                            <?php include($tplBase . '/' . $_tplPage . '.php') ?>
                            <?php elseif (!$server): ?>
                            <div id="idServers">
                                <?php
                                include($tplBase . '/serversList.php');
                                ?>
                            </div>
                            <div id="idServersCopy" style="display:none"></div>
                            <?php
                            if ($tplVars['_tplMain'] != 'ajax') {
                                require_once $tplBase . '/modalAddServer.php';
                                require_once $tplBase . '/modalFilterServer.php';
                            }
                            ?>
                        <?php elseif (!$tube):
                            ?>
                            <div id="idAllTubes">
                                <?php require_once $tplBase . '/allTubes.php'; ?>
                                <?php require_once $tplBase . '/modalClearTubes.php'; ?>
                            </div>
                            <div id='idAllTubesCopy' style="display:none"></div>
                        <?php elseif (!in_array($tube, $tubes)):
                            ?>
                            <?php echo sprintf('Tube "%s" not found or it is empty', $tube) ?>
                            <br><br><a href="./?server=<?php echo $server ?>"> << back </a>
                        <?php else: ?>
                            <?php require_once $tplBase . '/currentTube.php'; ?>
                            <?php require_once $tplBase . '/modalAddJob.php'; ?>
                            <?php require_once $tplBase . '/modalAddSample.php'; ?>
                        <?php endif; ?>
                        
                        <?php if (!isset($_tplPage)) { ?>
                            <?php require_once $tplBase . '/modalFilterColumns.php'; ?>
                        <?php } ?>
                        <?php require_once $tplBase . '/modalSettings.php'; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('scripts')
<script>
    var url = "./?server=<?php echo $server ?>";
    var contentType = "<?php echo isset($contentType) ? $contentType : '' ?>";
</script>
@append

@section('footer')
<script src="{{ url('/vendor/beanstalk_console/js/jquery.color.js') }}"></script>
<script src="{{ url('/vendor/beanstalk_console/js/jquery.cookie.js') }}"></script>
<script src="{{ url('/vendor/beanstalk_console/js/jquery.regexp.js') }}"></script>
<script src="{{ url('/vendor/beanstalk_console/js/customer.js') }}"></script>
@append
