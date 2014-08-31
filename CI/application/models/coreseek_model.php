<?php
require ("sphinxapi.php");

class Search_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
   
   function search(&$message)
   {
       $keyword = $this->input->post('keyword');
       $cl = new SphinxClient ();
       $cl->SetServer ( '127.0.0.1', 9312);
       //以下设置用于返回数组形式的结果
       $cl->SetArrayResult ( true );   
       /*
       //ID的过滤
       $cl->SetIDRange(3,4);
  
       //sql_attr_uint等类型的属性字段，需要使用setFilter过滤，类似SQL的WHERE        group_id=2
       $cl->setFilter('group_id',array(2));
       
       //sql_attr_uint等类型的属性字段，也可以设置过滤范围，类似SQL的WHERE        group_id2>=6 AND group_id2<=8
       $cl->SetFilterRange('group_id2',6,8);
       */
       //取从头开始的前20条数据，0,20类似SQl语句的LIMIT 0,20
       
       //在做索引时，没有进行 sql_attr_类型        设置的字段，可以作为“搜索字符串”，进行全文搜索
         //       "*"表示在所有索引里面同时搜索，"索引名称（例如test或者test,       test2）"则表示搜索指定的
  
       //如果需要搜索指定全文字段的内容，可以使用扩展匹配模式：
       //$cl->SetMatchMode(SPH_MATCH_EXTENDED);
       //$res=cl->Query( '@title (测试)' , "*");
       //$res=cl->Query( '@title (测试) @content ('网络')' , "*");

       /*搜索人名*/
       $cl->SetLimits(0,4);
       $cl->SetMatchMode(SPH_SORT_RELEVANCE);
       $res = $cl->Query ( $keyword, "user,user_delta" );  
       if (isset($res['matches']))
       {
          $message['user'] = $this->get_result($res['matches'],'user_profile','realname');
       }
       else
       {
          $message['user'] = "";
       }
       //print_r($res);
       // print_r($cl->GetLastError());
       // print_r($cl->GetLastWarning());
       // $message['matches_1'] = $res['total'];

       /*搜索问题*/
       $cl->SetLimits(0,4);
       $cl->SetMatchMode(SPH_SORT_RELEVANCE);
       $cl->SetSortMode(SPH_SORT_EXTENDED,"@weight DESC,@id DESC");
       $res = $cl->Query ( $keyword, "question,question_delta" );
       if (isset($res['matches']))
       {
          $message['question'] = $this->get_result($res['matches'],'q2a_question','title');
       }
       else
       {
          $message['question'] = "";
       }

       /*搜索标签*/
       $cl->SetLimits(0,4);
       $cl->SetMatchMode(SPH_SORT_RELEVANCE);
       $res = $cl->Query ( $keyword, "tag,tag_delta" );
       if (isset($res['matches']))
       {
          $message['tag'] = $this->get_result($res['matches'],'tag_type','tagname');
       }
       else
       {
          $message['tag'] = "";
       }


       return TRUE;
    }

    function search_user(&$message)
    { 
       $keyword = $this->input->post('keyword');
       $limit = $this->input->post('limit');
       $offset = $this->input->post('offset');
       $limit = (int)$limit;
       $offset = (int)$offset;

       $cl = new SphinxClient();
       $cl->SetServer ( '127.0.0.1', 9312);
       $cl->SetArrayResult ( true );
       $cl->SetLimits($offset,$limit);
       $cl->SetMatchMode(SPH_SORT_RELEVANCE);
       $res = $cl->Query ( $keyword, "user,user_delta" );  
       if (isset($res['matches']))
       {
          $message = $this->get_result($res['matches'],'user_profile','realname');
       }
       else
       {
          $message = "";
       }
       return TRUE;
    } 
   
    function search_question(&$message)
    {
       $keyword = $this->input->post('keyword');
       $limit = $this->input->post('limit');
       $offset = $this->input->post('offset');
       $limit = (int)$limit;
       $offset = (int)$offset;

       $cl = new SphinxClient ();
       $cl->SetServer ( '127.0.0.1', 9312);
       $cl->SetArrayResult ( true );
       $cl->SetLimits($offset,$limit);
       $cl->SetMatchMode(SPH_SORT_RELEVANCE);
       $cl->SetSortMode(SPH_SORT_EXTENDED,"@weight DESC,@id DESC");
       $res = $cl->Query ( $keyword, "question,question_delta" );
       if (isset($res['matches']))
       {
          $message = $this->get_result($res['matches'],'q2a_question','title');
       }
       else
       {
          $message = "";
       }
       return TRUE;
    }
   

    function search_tag(&$message)
    {
       $keyword = $this->input->post('keyword');
       $limit = $this->input->post('limit');
       $offset = $this->input->post('offset');
       $limit = (int)$limit;
       $offset = (int)$offset;

       $cl = new SphinxClient ();
       $cl->SetServer ( '127.0.0.1', 9312);
       $cl->SetArrayResult ( true );
       $cl->SetLimits($offset,$limit);
       $cl->SetMatchMode(SPH_SORT_RELEVANCE);
       $res = $cl->Query ( $keyword, "tag,tag_delta" );
       if (isset($res['matches']))
       {
          $message = $this->get_result($res['matches'],'tag_type','tagname');
       }
       else
       {
          $message = "";
       }
       return TRUE;
    }

    function get_result(&$matches,$table,$key)
    {
        $result = "";
        $num = count($matches);
        if ($table == "tag_type") 
        {
            $id = 'tagid';
        }
        else
        {
            $id = 'id';
        }

        for ($i = 0;$i < $num; ++$i)
        {
           $this->db->select("$id,$key");
           $this->db->where($id,$matches[$i]['id']);
           $query = $this->db->get($table);
           $result[$i] = $query->row_array();
        }
       
        return $result;
    }


}