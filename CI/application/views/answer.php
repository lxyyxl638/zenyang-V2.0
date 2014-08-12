<html>
<head></head>
	<?php echo form_open('q2a/answer/'.$question['id']) ?>

	<table border = "1">
		<tr>
			<td>
				<?php echo $question['title']?></td>
		</tr>
		<tr>
			<td>
				<?php echo $question['text']?></td>
		</tr>
		<?php foreach ($answer as $answer_item):?>
		<tr>
			<td>
				&nbsp &nbsp
				<?php echo $answer_item['answer'] ?></td>
			<td>
				<?php echo anchor('q2a/good/'.$question['id'],'赞 &nbsp');echo $answer_item['good'] ?></td>
			<td>
				<?php echo anchor('q2a/bad/'.$question['id'],'踩 &nbsp');echo $answer_item['bad'] ?></td>
		</tr>
		<?php endforeach ?>
		<tr>
			<td>
				<textarea type = "text" name = "text"  cols = "100" rows = "10"></textarea>
			</td>
		</tr>
		<tr>
			<td>
				<input type = "submit" value = "提交回答"></td>
		</tr>
	</table>
	<?php echo anchor('q2a/home',"返回")?>
</html>