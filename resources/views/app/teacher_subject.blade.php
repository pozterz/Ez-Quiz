@extends('app.template') @section('content')
<div class="container" ng-app="Teacher">
		<div class="tile is-ancestor" ng-controller="PanelController as panel">
				<div class="tile is-vertical is-3 is-parent">
						<div class="tile is-child box">
								<aside class="menu">
										<p class="menu-label">
												วิชา
										</p>
										<ul class="menu-list">
												<li>
													<a href="#" ng-click="panel.selectTab(2)" ng-class="{ 'is-active':panel.isSelected(2) }">
														<i class="icon is-small fa fa-plus"></i> เพิ่มวิชา
													</a>
												</li>
												<li>
													<a href="#" ng-click="panel.selectTab(1);" ng-class="{ 'is-active':panel.isSelected(1) }">
														<i class="icon is-small fa fa-clone"></i> วิชาทั้งหมด <span class="tag is-danger is-small" ng-model="subjects.length"><%subjects.length%></span>
													</a>
												</li>
										</ul>
										<p class="menu-label">
											รายวิชา
										</p>
										<ul class="menu-list">
											<li>
												<ul>
													<li ng-show="!subjects.length">
														<a href="#">ไม่พบวิชาที่สร้างไว้</a>
													</li>
													<li ng-repeat="subject in subjects">
														<a href="#" ng-click="panel.getSubjectData(subject.id); panel.selectTab(9); panel.selectMenuTab(1);"> <% subject.name  | limitTo: 15 %><%subject.name.length > 15 ? '...' : ''%>
															<span class="tag is-light-blue is-small"  ng-model="subject.quiz.length"><% subject.quiz.length %></span>
														</a>
													</li>
												</ul>
											</li>
										</ul>
										<p class="menu-label">
												แบบทดสอบ
										</p>
										<ul class="menu-list">
												<li>
													<a href="#" ng-click="panel.selectTab(3)" ng-class="{ 'is-active':panel.isSelected(3) }">
														<i class="icon is-small fa fa-plus"></i> เพิ่มแบบทดสอบ
													</a>
												</li>
												<li>
														<a href="#" ng-click="panel.selectTab(4);" ng-class="{ 'is-active':panel.isSelected(4) }">
														<i class="icon is-small fa fa-book"></i> แบบทดสอบทั้งหมด <span class="tag is-danger is-small" ng-model="quizcount">{{$quizcount}}</span>
														</a>
														<ul>
																<li>
																	<a href="#" ng-click="panel.selectTab(5)" ng-class="{ 'is-active':panel.isSelected(5) }">กำลังทำงาน</a>
																</li>
																<li>
																	<a href="#" ng-click="panel.selectTab(6)" ng-class="{ 'is-active':panel.isSelected(6) }">สิ้นสุดแล้ว</a>
																</li>
														</ul>
												</li>
										</ul>
										<p class="menu-label">
												จัดการ
										</p>
										<ul class="menu-list">
												<li>
													<a href="#" ng-click="panel.selectTab(7)" ng-class="{ 'is-active':panel.isSelected(7) }">
														<i class="icon is-small fa fa-user-plus"></i> เพิ่มผู้เรียน
														</a>
												</li>
												<li>
													<a href="#" ng-click="panel.selectTab(8)" ng-class="{ 'is-active':panel.isSelected(8) }">
														<i class="icon is-small fa fa-user-times"></i> ลบผู้เรียน
													</a>
												</li>
										</ul>
								</aside>
						</div>
				</div>
				<!-- all subject -->
				<div class="tile is-parent" ng-show="panel.isSelected(1)">
						<div class="tile is-child box">
							<article class="media">
								<div class="media-content">
									<div class="content">
										<loading></loading>
										<div class="notification fade is-primary" ng-show="!subjects.length && !loading">
											ไม่พบวิชาของคุณในฐานข้อมูล
										</div>
										<div ng-hide="loading">
											<table class="table" ng-show="subjects.length" >
												<caption>
													<div class="column is-4 is-offset-8">
														<p class="control has-icon has-icon-right is-pulled-right">
															<input type="text" class="input" ng-model="search">
															<i class="fa fa-search icon"></i>
														</p>
															<br/><br/>
													</div>
												</caption>
												<thead>
													<tr >
														<th ng-click="panel.sortBy('$index')">
															# <i class="fa fa-sort" ng-show="sort === '$index'" ng-class="{reverse: reverse}"></i>
														</th>
														<th ng-click="panel.sortBy('name')">
															ชื่อวิชา <i class="fa fa-sort" ng-show="sort === 'name'" ng-class="{reverse: reverse}"></i>
														</th>
														<th ng-click="panel.sortBy('subject_number')">
															รหัสวิชา <i class="fa fa-sort" ng-show="sort === 'subject_number'" ng-class="{reverse: reverse}"></i>
														</th>
														<th ng-click="panel.sortBy('created_at')">
															เวลาที่สร้าง <i class="fa fa-sort" ng-show="sort === 'created_at'" ng-class="{reverse: reverse}"></i>
														</th>
														<th>
														</th>
													</tr>
												</thead>
												<tbody>
													<tr ng-repeat="subject in subjects | filter:search:strict | orderBy:sort:reverse">
														<td><% $index+1 %></td>
														<td><% subject.name  %></td>
														<td><% subject.subject_number %></td>
														<td><% panel.convertTime(subject.created_at) | date:'d MMMM y HH:mm น.' %></td>
														<td><button type="button" class="button is-danger is-outlined" ng-click="panel.deleteSubject(subject.id)">ลบ</button></td>
													</tr>
												</tbody>
											</table>
										</div>
									</div>
								</div>
							</article>
						</div>
				</div>
				<!-- add subject -->
				<div class="tile is-parent" ng-show="panel.isSelected(2)">
						<div class="tile is-child box">
								<div class="content">
									<div class="notification fade is-primary" ng-show="notification" ng-if="post_datas.length">
										 <button class="delete" ng-click="notification = false"></button>
										 <p ng-repeat="message in post_datas">
										 	 <% message.message %>
										 </p>
									</div>
									<div ng-repeat="stuff in stuffs" class="control columns">
											<div class="column is-one-quarter">
												<input type="text" class="input is-primary" placeholder="รหัสวิชา" ng-model="stuff.subject_number">
											</div>
											<div class="column is-two-thirds">
												<input type="text" class="input is-primary" placeholder="ชื่อวิชา" ng-model="stuff.name">
											</div>
											<div class="column">
												<button type="button" class="button is-danger is-outlined" ng-click="panel.rmStuff($index)"><i class="fa fa-times"></i></button>
											</div>
									</div>
									<button type="button" class="button is-info is-outlined" ng-click="panel.addStuff()">เพิ่มวิชา</button>
									<button type="button" class="button is-success is-outlined" ng-click="panel.confirmStuff('newSubject');">ยืนยัน</button>
									<br/><br/>
									
								</div>
						</div>
				</div>
				<!-- Specific Subject -->
				<div class="tile is-parent" ng-show="panel.isSelected(9)">
						<div class="tile is-child box">
							<article class="media">
								<div class="media-content">
									<div class="content">
										<loading></loading>
										<div class="notification fade is-danger" ng-show="resType == 'failed'" >
										 <button class="delete" ng-click="notification = false"></button>
											 <p>
											 	 <% subjectData %>
											 </p>
										</div>
										<div ng-hide="loading || resType == 'failed'">
											<div class="columns">
													<div class="column">
															<h2><% subjectData[0].subject_number %> : <% subjectData[0].name %> </h2>
													</div>
											</div>
											<div class="columns">
												<div class="column has-text-centered">
										      <button class="button is-primary" ng-click="panel.selectMenuTab(1)" ng-class="panel.isMenuSelected(1)?'is-active':'is-outlined'">
										        <span class="icon is-small"><i class="fa fa-users"></i></span>
										        <span>สมาชิก</span>
										      </button>
										     </div>
										     <div class="column has-text-centered">
										      <button class="button is-info is-outlined" ng-click="panel.selectMenuTab(2)"  ng-class="panel.isMenuSelected(2)?'is-active':'is-outlined'">
										        <span class="icon is-small"><i class="fa fa-book"></i></span>
										        <span>แบบทดสอบ</span>
										      </button>
										      </div>
										      <div class="column has-text-centered">
										      <button class="button is-dark is-outlined" ng-click="panel.selectMenuTab(3)"  ng-class="panel.isMenuSelected(3)?'is-active':'is-outlined'">
										        <span class="icon is-small"><i class="fa fa-edit"></i></span>
										        <span>แก้ไข</span>
										      </button>
										      </div>
											</div>
											<!-- member menu -->
											<div ng-show="panel.isMenuSelected(1)">
												<div class="notification is-danger" ng-show="!subjectData[0].member.length">
													  ไม่มีสมาชิกในวิชานี้
									      </div>
												<table class="table">
													<thead ng-hide="!subjectData[0].member.length">
														<tr>
															<th ng-click="panel.sortBy('$index')">
																# <i class="fa fa-sort" ng-show="sort === '$index'" ng-class="{reverse: reverse}"></i>
															</th>
															<th ng-click="panel.sortBy('student_id')">รหัศนักศึกษา
																<i class="fa fa-sort" ng-show="sort === 'student_id'" ng-class="{reverse: reverse}"></i>
															</th>
															<th ng-click="panel.sortBy('name')">ชื่อ
																<i class="fa fa-sort" ng-show="sort === 'name'" ng-class="{reverse: reverse}"></i>
															</th>
															<th>ลบออกจากวิชา</th>
														</tr>
													</thead>
													<tbody>
														<tr ng-repeat="member in subjectData[0].member | orderBy:sort:reverse">
															<td><% $index+1 %></td>
															<td><% member.student_id %></td>
															<td><% member.name %></td>
															<td>
																<button type="button" class="button is-danger is-outlined " ng-click="panel.rmMember(subjectData[0].id,member.id)"><i class="fa fa-times"></i> &nbsp; ลบ</button>
															</td>
														</tr>
													</tbody>
												</table>
											</div>
											<!-- quizzes menu -->
											<div ng-show="panel.isMenuSelected(2)">
												<div class="notification is-danger" ng-show="!subjectData[0].quiz.length">
													 ไม่พบแบบทดสอบในวิชานี้
										    </div>
												<table class="table">
													<thead ng-hide="!subjectData[0].quiz.length">
														<tr >
															<th ng-click="panel.sortBy('$index')">
																# <i class="fa fa-sort" ng-show="sort === '$index'" ng-class="{reverse: reverse}"></i>
															</th>
															<th ng-click="panel.sortBy('name')">
																ชื่อแบบทดสอบ <i class="fa fa-sort" ng-show="sort === 'name'" ng-class="{reverse: reverse}"></i>
															</th>
															<th ng-click="panel.sortBy('quiz_time')">
																เวลา <i class="fa fa-sort" ng-show="sort === 'quiz_time'" ng-class="{reverse: reverse}"></i>
															</th>
															<th ng-click="panel.sortBy('quiz_qa')">
																จำนวนข้อ <i class="fa fa-sort" ng-show="sort === 'quiz_qa'" ng-class="{reverse: reverse}"></i>
															</th>
															<th ng-click="panel.sortBy('start')">
																เวลาเริ่มต้น <i class="fa fa-sort" ng-show="sort === 'subject.name'" ng-class="{reverse: reverse}"></i>
															</th>
															<th ng-click="panel.sortBy('end')">
																เวลาสิ้นสุด <i class="fa fa-sort" ng-show="sort === 'end'" ng-class="{reverse: reverse}"></i>
															</th>
															<th ng-click="panel.sortBy('level')">	
																ระดับความยาก <i class="fa fa-sort" ng-show="sort === 'level'" ng-class="{reverse: reverse}"></i>
															</th>
															<th>	
																คะแนน 
															</th>
														</tr>
													</thead>
													<tbody>
														<tr ng-repeat="quiz in subjectData[0].quiz | filter:search:strict | orderBy:sort:reverse">
															<td><% $index+1 %></td>
															<td><% quiz.name %></td>
															<td><% quiz.quiz_time %> นาที</td>
															<td><% quiz.quiz_qa.length %></td>
															<td><% panel.convertTime(quiz.start) | date:'d MMMM y HH:mm น.' %></td>
															<td><% panel.convertTime(quiz.end) | date:'d MMMM y HH:mm น.' %></td>
															<td><i class="fa fa-star" ng-repeat="x in [] | range:quiz.level"></i></td>
															<td>
																<button class="button is-outlined" ng-click="showModal($index)" >
																	<i class="fa fa-search"></i>
																</button>
															</td>
														</tr>
													</tbody>
												</table>
												<div class="modal" ng-class="{ 'is-active':modal }">
												  <div class="modal-background"></div>
												  <div class="modal-card">
												    <header class="modal-card-head">
												      <p class="modal-card-title">ตารางคะแนน</p>
												      <button class="delete" ng-click="closeModal()"></button>
												    </header>
												    <section class="modal-card-body">
												      <!-- Content ... -->
												      <div class="notification is-danger" ng-show="!subjectData[0].quiz[answer_index].answer.length">
												      	ยังไม่มีผู้ตอบคำถามในแบบทดสอบนี้
												      </div>
												      <table class="table" ng-show="subjectData[0].quiz[answer_index].answer.length">
												      	<thead>
												      		<tr>
												      			<th ng-click="panel.sortBy('$index')">
																			# <i class="fa fa-sort" ng-show="sort === '$index'" ng-class="{reverse: reverse}"></i>
																		</th>
												      			<th ng-click="panel.sortBy('student_id')">รหัสนักศึกษา
												      				<i class="fa fa-sort" ng-show="sort === 'student_id'" ng-class="{reverse: reverse}">
												      			</th>
												      			<th ng-click="panel.sortBy('name')">ชื่อ
																			<i class="fa fa-sort" ng-show="sort === 'name'" ng-class="{reverse: reverse}">
												      			</th>
												      			<th ng-click="panel.sortBy('point')">คะแนน
																			<i class="fa fa-sort" ng-show="sort === 'point'" ng-class="{reverse: reverse}">
												      			</th>
												      			<th ng-click="panel.sortBy('spendtime')">เวลา
																			<i class="fa fa-sort" ng-show="sort === 'spendtime'" ng-class="{reverse: reverse}">
												      			</th>
												      		</tr>
												      	</thead>
												      	<tbody>
												      		<tr ng-repeat="Answer in subjectData[0].quiz[answer_index].answer | orderBy:sort:reverse">
												      			<td><% $index+1 %></td>
												      			<td><% Answer.student_id %></td>
												      			<td><% Answer.name %></td>
												      			<td><% Answer.pivot.point %></td>
												      			<td><% Math.floor(Answer.pivot.spendtime/60) %> นาที <% Answer.pivot.spendtime%60 %> วินาที</td>
												      		</tr>
												      	</tbody>
												      </table>
												    </section>
												    <footer class="modal-card-foot">
												      <a class="button is-danger is-outlined" ng-click="closeModal()">ปิดหน้านี้</a>
												    </footer>
												  </div>
												</div>
											</div>
											<!-- edit menu -->
											<div ng-show="panel.isMenuSelected(3)">
												<br/>
												<div class="box">
													<div class="notification" ng-show="post_datas.length" ng-class="(post_datas[0].type == 'failed')?'is-danger':'is-success'">
														<% post_datas[0].message %>
													</div>
													<div class="columns">
														<div class="column is-2">
															<label class="label">รหัสวิชา</label>
															<input type="text" class="input" ng-model="edit[0].subject_number">
														</div>
														<div class="column">
															<label class="label">ชื่อวิชา</label>
															<input type="text" class="input" ng-model="edit[0].name">
														</div>
													</div>
													<button type="button" class="button is-success is-outlined" ng-click="panel.editSubject()">ยืนยัน</button><br/><br/>
												</div>
											</div>
										</div>
									</div>
								</div>
							</article>
						</div>
					</div>
				<!-- add quiz -->
				<div class="tile is-parent" ng-show="panel.isSelected(3)">
					<div class="tile is-child box">
						<div class="columns">
								<div class="control column">
									<div class="notification fade is-danger" ng-show="!subjects.length">ไม่พบวิชาของคุณ กรุณาสร้างวิชาก่อนที่จะเพิ่มแบบทดสอบ</div>
								</div>
							</div>
						<div class="content" ng-show="subjects.length">
							<div class="columns">
								<div class="control column">
									<div class="notification fade is-danger" ng-show="errmsg"><% errmsg %></div>
								</div>
							</div>
							<div class="columns">
								<div class="control column">
									<input type="text" class="input" name="name" ng-model="quiz.name" placeholder="ชื่อแบบทดสอบ">
								</div>
								<div class="control column is-2">
									<p class="control has-icon has-icon-right">
										<input type="number" class="input" min="1" max="60" name="time" ng-model="quiz.time" placeholder="ระดับความยาก">
										<i class="fa fa-clock-o"></i>
									</p>
								</div>
								<div class="control column">
									<span class="select">
									  <select  ng-model="quiz.subject_id">
									    <option ng-repeat="subject in subjects" value="<% subject.id %>"><% subject.name | limitTo: 17 %><%subject.name.length > 17 ? '...' : ''%></option>
									  </select>
									</span>
								</div>
								<div class="column is-2">
									<p class="control has-icon has-icon-right">
										<input type="number" class="input" min="1" max="6" name="level" ng-model="quiz.level" placeholder="ระดับความยาก">
										<i class="fa fa-star"></i>
									</p>
								</div>
							</div>
							<div class="columns">
								<div class="control column">
									<p><label for="" class="label">วันที่เริ่มต้น</label></p>
									<input type="date" class="input" name="start" ng-model="quiz.start">
								</div>
								<div class="control column">
									<p><label for="" class="label">เวลา</label></p>
									<input type="time" class="input" name="starttime" ng-model="quiz.starttime">
								</div>
								<div class="control column">
									<p><label for="" class="label">วันที่สิ้นสุด</label></p>
									<input type="date" class="input" name="end" ng-model="quiz.end">
								</div>
								<div class="control column">
									<p><label for="" class="label">เวลา</label></p>
									<input type="time" class="input" name="endtime" ng-model="quiz.endtime">
								</div>
							</div>
							<div class="notification" ng-repeat="question in quiz.question">
								<button class="delete" ng-click="panel.rmQuestion($index)"></button>
								<div class="columns">
									<div class="column is-10">
										<div class="control">
										<label class="label">ข้อที่ <span><% $index+1 %></span></label>
										<label class="label">คำถาม<span></span></label>
										<input type="text" class="input" name="question" ng-model="question.ask" placeholder="คำถาม">
										</div>
									</div>
								</div>
								<label class="label">ตัวเลือก<span></span></label>
								<div class="columns" ng-repeat="choice in question.choices">
									<div class="column is-8">
										<div class="control">
										<input type="text" class="input" name="choice" ng-model="choice.text"  placeholder="ตัวเลือก">
										</div>
									</div>
									<div class="column is-2">
										<p class="control">
											<button type="button" class="button is-outlined" ng-click="panel.correctChoice($parent.$index,$index)" ng-class="{ 'is-success':choice.isCorrect }"><i class="fa fa-check"></i></button>
											<button type="button" class="button is-danger is-outlined" ng-click="panel.rmChoice($parent.$index,$index)"><i class="fa fa-times"></i> &nbsp; ลบ</button>
									  </p>
									</div>
								</div>
								<button type="button" class="button is-danger is-outlined" ng-click="panel.addChoice($index)"><i class="fa fa-plus"></i> &nbsp; เพิ่มตัวเลือก</button>
							</div>
							<div class="columns">
								<div class="column">
									<button type="button" class="button is-info is-outlined" ng-click="panel.addQuestion()">เพิ่มคำถาม</button>
									<button type="button" class="button is-success is-outlined is-pulled-right" ng-click="panel.confirmQuiz('newQuizzes')">ยืนยัน</button>
								</div>
							</div>
							<br/><br/>
								<div class="notification fade is-primary" ng-show="notification" ng-if="post_datas.length">
									 <button class="delete" ng-click="notification = false"></button>
									 <p ng-repeat="message in post_datas">
									 	 <% message.message %>
									 </p>
								</div>
						</div>
					</div>
				</div>
				<!-- all quiz -->
				<div class="tile is-parent" ng-show="panel.isSelected(4)">
						<div class="tile is-child box">
							<article class="media">
								<div class="media-content">
									<div class="content">
										<loading></loading>
										<div class="notification fade is-primary" ng-show="!quizzes.length && !loading">
											ไม่พบคำถามในฐานข้อมูล
										</div>
										<div class="table" ng-hide="loading">
											<table class="table" ng-show="quizzes.length" >
												<caption>
													<div class="column is-4 is-offset-8">
														<p class="control has-icon has-icon-right is-pulled-right">
															<input type="text" class="input" ng-model="search">
															<i class="fa fa-search icon"></i>
														</p>
															<br/><br/>
													</div>
												</caption>
												<thead>
													<tr >
														<th ng-click="panel.sortBy('$index')">
															# <i class="fa fa-sort" ng-show="sort === '$index'" ng-class="{reverse: reverse}"></i>
														</th>
														<th ng-click="panel.sortBy('name')">
															ชื่อแบบทดสอบ <i class="fa fa-sort" ng-show="sort === 'name'" ng-class="{reverse: reverse}"></i>
														</th>
														<th ng-click="panel.sortBy('subject.name')">
															รหัสวิชา / ชื่อวิชา <i class="fa fa-sort" ng-show="sort === 'subject.name'" ng-class="{reverse: reverse}"></i>
														</th>
														<th ng-click="panel.sortBy('end')">
															เวลาสิ้นสุด <i class="fa fa-sort" ng-show="sort === 'end'" ng-class="{reverse: reverse}"></i>
														</th>
														<th ng-click="panel.sortBy('level')">	
															ระดับความยาก <i class="fa fa-sort" ng-show="sort === 'level'" ng-class="{reverse: reverse}"></i>
														</th>
													</tr>
												</thead>
												<tbody>
													<tr ng-repeat="quiz in quizzes | filter:search:strict | orderBy:sort:reverse">
														<td><% $index+1 %></td>
														<td><% quiz.name  %></td>
														<td><% quiz.subject.subject_number %> : <% quiz.subject.name  %></td>
														<td><% panel.convertTime(quiz.end) | date:'d MMMM y HH:mm น.' %></td>
														<td><i class="fa fa-star" ng-repeat="x in [] | range:quiz.level"></i></td>
													</tr>
												</tbody>
											</table>
										</div>
									</div>
								</div>
							</article>
						</div>
				</div>
				<!-- active quiz -->
				<div class="tile is-parent" ng-show="panel.isSelected(5)">
						<div class="tile is-child box">
							<article class="media">
								<div class="media-content">
									<div class="content">
										<loading></loading>
										<div class="notification fade is-primary" ng-show="!quizzes.length && !loading">
											ไม่พบคำถามที่กำลังทำงาน ในฐานข้อมูล
										</div>
										<div class="table" ng-hide="loading">
											<table class="table" ng-show="quizzes.length" >
												<caption>
													<div class="column is-4 is-offset-8">
														<p class="control has-icon has-icon-right is-pulled-right">
															<input type="text" class="input" ng-model="search">
															<i class="fa fa-search icon"></i>
														</p>
															<br/><br/>
													</div>
												</caption>
												<thead>
													<tr >
														<th ng-click="panel.sortBy('$index')">
															# <i class="fa fa-sort" ng-show="sort === '$index'" ng-class="{reverse: reverse}"></i>
														</th>
														<th ng-click="panel.sortBy('name')">
															ชื่อแบบทดสอบ <i class="fa fa-sort" ng-show="sort === 'name'" ng-class="{reverse: reverse}"></i>
														</th>
														<th ng-click="panel.sortBy('subject.name')">
															รหัสวิชา / ชื่อวิชา <i class="fa fa-sort" ng-show="sort === 'subject.name'" ng-class="{reverse: reverse}"></i>
														</th>
														<th ng-click="panel.sortBy('end')">
															เวลาสิ้นสุด <i class="fa fa-sort" ng-show="sort === 'end'" ng-class="{reverse: reverse}"></i>
														</th>
														<th ng-click="panel.sortBy('level')">	
															ระดับความยาก <i class="fa fa-sort" ng-show="sort === 'level'" ng-class="{reverse: reverse}"></i>
														</th>
													</tr>
												</thead>
												<tbody>
													<tr ng-repeat="quiz in quizzes | filter:search:strict | orderBy:sort:reverse" ng-show="panel.isActive(quiz.end)">
														<td><% $index+1 %></td>
														<td><% quiz.name  %></td>
														<td><% quiz.subject.subject_number %> : <% quiz.subject.name  %></td>
														<td><% panel.convertTime(quiz.end) | date:'d MMMM y HH:mm น.' %></td>
														<td><i class="fa fa-star" ng-repeat="x in [] | range:quiz.level"></i></td>
													</tr>
												</tbody>
											</table>
										</div>
									</div>
								</div>
							</article>
						</div>
				</div>
				<!-- ended quiz -->
				<div class="tile is-parent" ng-show="panel.isSelected(6)">
						<div class="tile is-child box">
							<article class="media">
								<div class="media-content">
									<div class="content">
										<loading></loading>
										<div class="notification fade is-primary" ng-show="!quizzes.length && !loading">
											ไม่พบคำถามที่สิ้นสุดการทำงานแล้ว ในฐานข้อมูล
										</div>
										<div class="table" ng-hide="loading">
											<table class="table" ng-show="quizzes.length" >
												<caption>
													<div class="column is-4 is-offset-8">
														<p class="control has-icon has-icon-right is-pulled-right">
															<input type="text" class="input" ng-model="search">
															<i class="fa fa-search icon"></i>
														</p>
															<br/><br/>
													</div>
												</caption>
												<thead>
													<tr >
														<th ng-click="panel.sortBy('$index')">
															# <i class="fa fa-sort" ng-show="sort === '$index'" ng-class="{reverse: reverse}"></i>
														</th>
														<th ng-click="panel.sortBy('name')">
															ชื่อแบบทดสอบ <i class="fa fa-sort" ng-show="sort === 'name'" ng-class="{reverse: reverse}"></i>
														</th>
														<th ng-click="panel.sortBy('subject.name')">
															รหัสวิชา / ชื่อวิชา <i class="fa fa-sort" ng-show="sort === 'subject.name'" ng-class="{reverse: reverse}"></i>
														</th>
														<th ng-click="panel.sortBy('end')">
															เวลาสิ้นสุด <i class="fa fa-sort" ng-show="sort === 'end'" ng-class="{reverse: reverse}"></i>
														</th>
														<th ng-click="panel.sortBy('level')">	
															ระดับความยาก <i class="fa fa-sort" ng-show="sort === 'level'" ng-class="{reverse: reverse}"></i>
														</th>
													</tr>
												</thead>
												<tbody>
													<tr ng-repeat="quiz in quizzes | filter:search:strict | orderBy:sort:reverse" ng-show="!panel.isActive(quiz.end)">
														<td><% $index+1 %></td>
														<td><% quiz.name  %></td>
														<td><% quiz.subject.subject_number %> : <% quiz.subject.name  %></td>
														<td><% panel.convertTime(quiz.end) | date:'d MMMM y HH:mm น.' %></td>
														<td><i class="fa fa-star" ng-repeat="x in [] | range:quiz.level"></i></td>
													</tr>
												</tbody>
											</table>
										</div>
									</div>
								</div>
							</article>
						</div>
				</div>
				<!-- add member -->
				<div class="tile is-parent" ng-show="panel.isSelected(7)">
						<div class="tile is-child box">
							<article class="media">
								<div class="media-content">
									<div class="content">
										<p>xxxxxxxxx</p>
									</div>
								</div>
							</article>
						</div>
				</div>
				<!-- remove member -->
				<div class="tile is-parent" ng-show="panel.isSelected(8)">
						<div class="tile is-child box">
							<article class="media">
								<div class="media-content">
									<div class="content">
										<p>xxxxxxxxxx</p>
									</div>
								</div>
							</article>
						</div>
				</div>
		</div>
</div>
<br/><br/><br/><br/>
@endsection

@section('js')
<script>
		(function(){
				var app = angular.module('Teacher', ['ngAnimate','ngLocale','angularUtils.directives.dirPagination'], function($interpolateProvider)
					{
							$interpolateProvider.startSymbol('<%');
							$interpolateProvider.endSymbol('%>');
					});

				// directive
				app.directive('loading', function () {
			      return {
			        restrict: 'E',
			        replace:true,
			        template: '<div class="loading has-text-centered"><img src="{{url("/spinner.gif")}}" width="30%" /></div>',
			        link: function (scope, element, attr) {
			              scope.$watch('loading', function (val) {
			                  if (val)
			                      $(element).show();
			                  else
			                      $(element).hide();
			              });
			        }
			      }
			  })

				app.constant("CSRF_TOKEN", '{{ csrf_token() }}')

				app.filter('range', function() {
						return function(input, total) {
								total = parseInt(total);

								for (var i = 0; i < total; i++) {
										input.push(i);
								}

								return input;
						};
				});

				app.controller('PanelController',function($http,$scope,$window, CSRF_TOKEN)
				{
					$scope.modal = false;
					$scope.answer_index = 0;
					$scope.Math = window.Math;
					$scope.showModal = function(id){
						$scope.modal = true;
						$scope.answer_index = id;
						console.log(id);
					}

					$scope.closeModal = function(){
						$scope.modal = false;
					}

					this.selectTab = function(setTab)
					{
						this.tab = setTab;
						$scope.notification = false;
						var name = this.tabName(setTab)
						if(name){
							getData(name);
						}
						getSubject();
						getQuizzes();
					};

					this.menuTab = 0;
					this.selectMenuTab = function(setTab)
					{
						this.menuTab = setTab;
					};

					this.isMenuSelected = function(checkTab)
					{
						return this.menuTab === checkTab;
					}

					$scope.subjects = [];
					var getSubject = function()
					{
						$http.get("{{ url('/Teacher') }}"+'/getSubjects')
								.then(function(response)
								{
									$scope.subjects = response.data.result;
									//console.log($scope.subjects);
								})
					}

					var getQuizzes = function()
					{
						$http.get("{{ url('/Teacher') }}"+'/getQuizzes')
								.then(function(response)
								{
									$scope.quizzes = response.data.result;
									//console.log('getQuizzes'+$scope.quizzes);
								})
					}

					this.deleteSubject = function(id){
						if (confirm("ยืนยันการลบวิชานี้ ?")) {
							this.postData('deleteSubject',id);
						}
					}

					this.getSubjectData = function(id){

						$scope.loading = true;
						$http.get("{{ url('/Teacher/getSubjectData') }}"+'/'+id)
								.then(function(response)
								{
									$scope.subjectData = [];
									$scope.edit = [];
									$scope.subjectData = response.data.result;
									$scope.resType = response.data.type;
									$scope.loading = false;
									if($scope.resType == 'success'){
										$scope.edit.push({
											'subject_number': $scope.subjectData[0].subject_number,
											'name' : $scope.subjectData[0].name,
											'editID' : $scope.subjectData[0].id,
										});
									}
									console.log($scope.subjectData);

								})
					}

					this.editSubject = function(){
						this.postData('editSubject',$scope.edit);
					}

					var getData = function(url)
					{
						$scope.loading = true;
						$http.get("{{ url('/Teacher') }}"+'/'+url)
								.then(function(response)
								{
										$scope.datas = response.data.result;
										$scope.loading = false;

										$scope.quiz.subject_id = $scope.datas[0].id;
										//console.log($scope.datas);
								});
					}
					
					this.postData = function(url,data)
					{
						$http.post("{{ url('/Teacher') }}"+'/'+url,{data : data,csrf_token: CSRF_TOKEN})
								.then(function(response)
								{
										$scope.post_datas = response.data.result;
										$scope.notification = true;
										
										//console.log($scope.post_datas);
								}).then(function(){
										getSubject();
										getQuizzes();
								});
					}

					this.tabName = function(number)
					{
						switch(number){
							case 1: return 0; break;
							case 2: return 0; break;
							case 3: return 0; break;
							case 4: return 0; break;
							case 5: return 'getActiveQuizzes'; break;
							case 6: return 'getInActiveQuizzes'; break;
							case 7: return 0; break;
							case 8: return 0; break;
							case 9: return 0; break;
							default : return 0;
						}
					};

					@if(Request::is('Teacher/Subject'))
						@if(Request::get('subject'))
						 	this.getSubjectData({{ Request::get('subject') }});
						 	this.selectTab(9);
						 	this.selectMenuTab(1);
						@else
							this.tab = 1;
							this.selectTab(this.tab);
						@endif
					@else
						this.tab = 2;
						this.selectTab(this.tab);
					@endif

					this.isSelected = function(checkTab)
					{
						return this.tab === checkTab;
					}

					// Sort
					$scope.now = new Date();
					$scope.currentPage = 1;
					$scope.pageSize = 10;
					$scope.sort = 'end';
					$scope.reverse = false;

					this.sortBy = function(propertie)
					{
						$scope.reverse = ($scope.sort === propertie) ? !$scope.reverse : false;
						$scope.sort = propertie;
					}

					this.isActive = function(end)
					{
						var date = new Date();
						return date < this.convertTime(end);
					}

					this.convertTime = function(time)
					{
						var date = new Date(time);
						return date;
					}
					
					// new stuff & quiz
					$scope.stuffs = [];

					$scope.stuffs.push({
						name: "",
						subject_number: "",
					})

					this.rmMember = function(subject_id,user_id)
					{
							if (confirm("ยืนยันการลบผู้ใช้นี้ ?")) {
							var data = {
								'subject_id' : subject_id,
								'user_id' : user_id,
							}

							this.postData('removeMember',data);
							this.getSubjectData(subject_id);
							this.selectMenuTab(1);
						}
					}

					this.addStuff = function()
					{
						$scope.stuffs.push({
							name: "",
							subject_number: "",
						})
						//console.log($scope.stuffs);
					}

					this.rmStuff = function(index)
					{
						$scope.stuffs.splice(index,1);
						//console.log(index);
					}

					$scope.notification = false;
					this.confirmStuff = function(url)
					{
						var tmp = [];
						for(var i in $scope.stuffs){
							if(($scope.stuffs[i].name.length > 0 && $scope.stuffs[i].subject_number.length > 0) && $scope.stuffs[i].name != '' && $scope.stuffs[i].subject_number.length != ''){
								tmp.push($scope.stuffs[i]);
							}
						}
						
						$scope.stuffs = tmp;
						//console.log($scope.stuffs);
						this.postData(url,$scope.stuffs);
					}

					$scope.subjectcount = {{ $subjectcount }};
					$scope.quizcount = {{ $quizcount }};

					$scope.quiz = [];
					$scope.quiz.name = '';
					$scope.quiz.subject_id;
					$scope.quiz.time = 1;
					$scope.quiz.level = 1;
					$scope.quiz.start = new Date();
					$scope.quiz.starttime = new Date(1970, 0, 1, 0, 0, 0);;
					$scope.quiz.end = new Date();
					$scope.quiz.endtime = new Date(1970, 0, 1, 0, 0, 0);
					$scope.quiz.question = [];

					//console.log($scope.quiz);

					this.addQuestion = function()
					{
						$scope.quiz.question.push({
							ask: "",
							answer: -1,
							choices: [],
						})
						//console.log($scope.quiz);
					}

					this.rmQuestion = function(index)
					{
						$scope.quiz.question.splice(index,1);
						//console.log(index);
					}

					this.addChoice = function(index)
					{
						$scope.quiz.question[index].choices.push({
							text: "",
							isCorrect: false,
						})
						//console.log($scope.quiz.question[index].choices);
					}

					this.rmChoice = function(parent,index)
					{
						$scope.quiz.question[parent].choices.splice(index,1);
					}

					this.correctChoice = function(parent,index)
					{
						for(i in $scope.quiz.question[parent].choices){
							if(i != index)
								$scope.quiz.question[parent].choices[i].isCorrect = false;
						}
						$scope.quiz.question[parent].choices[index].isCorrect = true;
						$scope.quiz.question[parent].answer = index;
						//console.log($scope.quiz.question[parent].answer);
					}

					

					this.confirmQuiz = function(url)
					{
						var $tmp = {};
						$scope.errmsg = false;
						
						if($scope.quiz.name == '' || $scope.quiz.name.length == 0)
							return $scope.errmsg = 'กรุณากรอกชื่อของแบบทดสอบ';
						if($scope.quiz.question.length == 0)
							return $scope.errmsg = 'กรุณาเพิ่มคำถาม';

						$tmp.name = $scope.quiz.name;
						$tmp.subject_id = $scope.quiz.subject_id;
						$tmp.time = $scope.quiz.time;
						$tmp.level = $scope.quiz.level;
						$tmp.start = $scope.quiz.start;
						$tmp.starttime = $scope.quiz.starttime;
						$tmp.end = $scope.quiz.end;
						$tmp.endtime = $scope.quiz.endtime;
						$tmp.question = [];
						for(i in $scope.quiz.question)
						{
							if($scope.quiz.question[i].ask == '' || $scope.quiz.question[i].ask == 0)
								return $scope.errmsg = "กรุณากรอกคำถามให้ครบถ้วน";
							if($scope.quiz.question[i].choices.length == 0)
								return $scope.errmsg = "กรุณาเพิ่มตัวเลือกให้ครบถ้วน";
							if($scope.quiz.question[i].answer == -1)
								return $scope.errmsg = "กรุณาเลือกคำตอบที่ถูกต้องให้ครบถ้วน";

							$tmp.question.push($scope.quiz.question[i]);
							var new_choices = [];
							//console.log($scope.quiz.question[i]);
							//console.log($scope.quiz.question[i]);
							for(j in $scope.quiz.question[i].choices)
							{
								if($scope.quiz.question[i].choices[j].text != '' || $scope.quiz.question[i].choices[j].length > 0){
									new_choices.push($scope.quiz.question[i].choices[j]);
								}
								else if($scope.quiz.question[i].choices[j].isCorrect){
									$tmp.question[i].answer = -1;
									return $scope.errmsg = "กรุณาเลือกคำตอบที่ถูกต้อง";
								}
							}

							$tmp.question[i].choices = new_choices;
							//console.log($tmp);
							for(j in $tmp.question[i].choices){
								if($tmp.question[i].choices[j].isCorrect)
									$tmp.question[i].answer = j;
							}
						}

						$scope.quiz = $tmp;
						//console.log($scope.quiz);
					  this.postData(url,$scope.quiz);
						
					}

				});
		})();

</script>

@endsection