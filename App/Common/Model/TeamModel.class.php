<?php
namespace Common\Model;
use Think\Model\RelationModel;
class TeamModel extends RelationModel{
    protected $trueTableName='team';

    protected const $type = ['队员','队长','教练','领队','队委','副领队','副队长','副教练']；





}
    