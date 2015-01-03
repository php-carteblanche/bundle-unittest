<?php
/**
 * This file is part of the CarteBlanche PHP framework.
 *
 * (c) Pierre Cassat <me@e-piwi.fr> and contributors
 *
 * License Apache-2.0 <http://github.com/php-carteblanche/carteblanche/blob/master/LICENSE>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace UnitTest\Lib;
use \UnitTest\Lib\TestCase;

/**
 * The test reporter
 */
class UnitTestReporter
{

	/**
	 * The test set title
	 */
	protected $title='Test Suite';

	/**
	 * Build some HTML information strings ?
	 */
	protected $html=true;

	/**
	 * Colors set for information strings
	 */
	protected $success_color='green';
	protected $failure_color='red';
	protected $warning_color='orange';

// ----------------------------------
// Masks
// ----------------------------------

	const CALLEDAT_MASK = " at [%s line %s]";

	const STRONG_MASK = "**%s**";
	const STRONG_MASK_HTML = "<strong>%s</strong>";

	const TITLE_MASK = "**%s**\n\n";
	const TITLE_MASK_HTML = '<h2 style="font-weight: bold;">%s</h2>';

	const STAMP_MASK = 'Test ID: %s | Executed at: %s';

	const SOURCE_CODE_MASK = "%s\n";
	const SOURCE_CODE_MASK_HTML = '<div style="border:1px solid %s;">%s</div>';

	const TEST_ITEM_MASK = "%s\n";
	const TEST_ITEM_MASK_HTML = '%s<br />';

	const RESULT_ITEM_MASK = '%2$s [%1$s] : ';
	const RESULT_ITEM_MASK_HTML = '<abbr style="font-weight: bold;" title="%s">%s</abbr> : ';

	const BLOCKINFO_MASK = "    %s\n";
	const BLOCKINFO_MASK_HTML = '<p style="margin: 1em 3em;font-size: .9em;">%s</p>';

	const ASSERT_MASK = "\n=> %s\n";
	const ASSERT_MASK_HTML = '<p style="color: white;background-color: %s;padding: 4px 1em;margin:0;">%s</p>';

	const RESULT_MASK = "\n=> %s\n";
	const RESULT_MASK_HTML = '<p style="color: white;background-color: %s;padding: 4px 1em;">%s</p>';

	const METHOD_MASK = ' [%s] ';
	const METHOD_MASK_HTML = ' [<span style="font-weight: bold;">%s</span>] ';

	const OVERVIEW_MASK = ' [%s]';
	const OVERVIEW_MASK_HTML = ' <span style="float:right;">%s</span>';

	const METHOD_DESCRIPTION_MASK = ' (%s) ';
	const METHOD_DESCRIPTION_MASK_HTML = ' (%s) ';

	const PASSED_INFO = ' *Passed*';
	const PASSED_INFO_HTML = ' <span style="color: green; font-weight: bold;">Passed</span>';

	const FAILED_INFO = ' *! Failed !*';
	const FAILED_INFO_HTML = ' <span style="color: red; font-weight: bold;">Failed</span>';

	/**
	 * Set the HTML object property
	 */	
	public function setHtml( $html )
	{
		$this->html = (bool) $html;
		return $this;
	}

	/**
	 */	
	public function setTitle( $title )
	{
		$this->title = (bool) $title;
		return $this;
	}

	/**
	 */	
	public function getTitle()
	{
		return $this->title;
	}

/////////////////////////////
// INFORMERS
/////////////////////////////
	
	/**
	 * Build the information string for an assertion
	 *
	 * @param object $assert The Assertion object
	 * @return string A full information string
	 */
	public function buildAssertInfo( \UnitTest\Lib\Assertion $assert )
	{
		$_info_str = $assert->getInfo();
		$_filename = $assert->getFilename();
		$_line = $assert->getline();
		$_code = $assert->getCode();
		$_result = $assert->getAssertSucceed();

		if (true===$_result)
		{
			$color = $this->success_color;
			$title = 'An assertion succeed';
		}
		else
		{
			$color = $this->failure_color;
			$title = 'An assertion failed';
		}
		$_info='';
		$_info .= sprintf(
			true===$this->html ? self::STRONG_MASK_HTML : self::STRONG_MASK, $title
		);
		$_info .= ' : '.$this->renderValueInfo($_code);

		if (!empty($_info_str))
		{
			$_info .= $this->renderMethodDescriptionInfo( 
				sprintf($_info_str, $this->renderValueInfo($_value_str))
			);
		}

		$_info .= ' '.$this->renderCalledInfo( $_filename, $_line );

		$full_info = sprintf(
			true===$this->html ? self::ASSERT_MASK_HTML : self::ASSERT_MASK, $color, $_info
		);
		if (class_exists('\CarteBlanche\App\Kernel')) 
		{
			$trace = \Dev\Profiler::buildTraces(array(0=>array(
				'message'=>$full_info,
				'source'=>\Dev\Profiler::getHighlightedSource($_filename, $_line),
				'highlighted'=>true
			)));
			return view(
				'UnitTest/views/message', 
				array(
					'message'=>\Dev\Profiler::formatMessage( $trace[1], $this->html ),
					'color'=>$color
				)
			);
		}
		else
		{
			if (!empty($_filename) && !empty($_line))
			{
				$full_info = sprintf(
					true===$this->html ? self::SOURCE_CODE_MASK_HTML : self::SOURCE_CODE_MASK,
					$color, 
					$full_info . \Dev\Profiler::getHighlightedSource($_filename, $_line)
				);
			}
			return $full_info;
		}
	}
	
	/**
	 * Build the information string for an assertion
	 *
	 * @param object $assert The Assertion object
	 * @return string A full information string
	 */
	public function buildUnitTestInfo( $array )
	{
		if (class_exists('\CarteBlanche\App\Kernel')) 
		{
			return view(
				'UnitTest/views/test_report', 
				array(
					'title'=>$this->getTitle(),
					'profiling_info' => \Dev\Profiler::renderProfilingInfo(),
					'stacks'=>$array
				)
			);
		}
		else
		{
			$str = '';
			foreach($array as $_id=>$group)
			{
				$str .= $group['info'].( !empty($group['presentation']) ? $group['presentation'] : '' );
				if (count($group['tests'])>0)
				{
					$str .= join(' ', $group['tests']);
				}
				$str .= $group['conclusion'];
			}
			return $str;
		}
	}
	
	/**
	 * Build the information string
	 *
	 * @param object $test The TestCase object
	 * @return string A full information string
	 */
	public function buildTestCaseInfo( \UnitTest\Lib\TestCase $test )
	{
		$_value_str = $test->getValue();
		$_meth_str = $test->getMethod();
		$_meth_desc = $test->getMethodDescription();
		$_info_str = $test->getInfo();
		$_id_str = $test->getId();
		$_timestamp = $test->getTime();
		$_time_str = $this->microDateTime($_timestamp);
		$_result_str = 'passed'===$test->getResult() ? $this->renderPassedInfo() : $this->renderFailedInfo();
		$_called_filename = $test->getFilename();
		$_called_line = $test->getLine();

		$_info='';
		$_title = $this->renderTestCaseStamp( $_id_str, $_time_str );
		$_info .= $this->renderResultInfo( $_result_str, $_title );
		$_info .= sprintf(
			true===$this->html ? self::STRONG_MASK_HTML : self::STRONG_MASK,
			sprintf($_info_str, (string) $test->getValue())
		);
		if (!empty($_meth_str))
		{
			if (!empty($_meth_desc))
			{
				$_info .= $this->renderMethodDescriptionInfo( 
					sprintf($_meth_desc, $this->renderValueInfo($_value_str))
				);
			} else {
				$_info .= $this->renderMethodInfo( $_meth_str );
			}
		}
		if (!empty($_called_filename))
		{
			$_info .= $this->renderCalledInfo( $_called_filename, $_called_line );
		}

		return $this->renderTestCaseItem($_info);
	}
	
	/**
	 * Build the information string
	 *
	 * @param object $test The TestGroup object
	 * @return string A full information string
	 */
	public function buildTestGroupInfo( \UnitTest\Lib\TestGroup $test )
	{
		$_info_str = $test->getInfo();
		$_meth_str = $test->getMethod();

		if (!is_null($_meth_str))
		{
			$info = sprintf($_info_str, $_meth_str);
		}
		else
		{
			$info = $_info_str;
		}
		$full_str = $this->renderGroupTitle( $info );
		return $full_str;
	}
	
	/**
	 * Build the information string
	 *
	 * @param object $test The TestGroup object
	 * @return string A full information string
	 */
	public function buildTestGroupPresentation( \UnitTest\Lib\TestGroup $test )
	{
		return $this->renderPresentationInfo( $test->getPresentation() );
	}
	
	/**
	 * Build the conclusion string
	 *
	 * @param object $test The TestGroup object
	 * @return string A full information string
	 */
	public function buildTestGroupConclusion( \UnitTest\Lib\TestGroup $test )
	{
		$global_count = $test->getCounter();
		$passed_count = $test->getCounter('passed');
		$failed_count = $test->getCounter('failed');
		$full_str = $this->renderConclusion( 
			(int) $global_count, 
			(int) $passed_count, 
			(int) $failed_count 
		);
		return $full_str;
	}
	
/////////////////////////////
// INFORMATION BUILDERS
/////////////////////////////

	/**
	 * Build the conclusion string
	 */	
	protected function renderCalledInfo( $filename, $line )
	{
		return sprintf(
			\Dev\Profiler::mask_trace_item_position_info,
			(string) \Dev\Profiler::formatPath($filename, true===$this->html ? 'html' : 'text'), 
			(int) $line
		);

	}
	
	/**
	 * Build the value information string
	 */	
	protected function renderValueInfo( $value )
	{
		return \Dev\Profiler::formatParam( array(
			'type'=>gettype($value),
			'value'=>$value
		), '', $this->html );
	}
	
	/**
	 * Build the conclusion string
	 */	
	protected function renderTestCaseStamp( $id, $time )
	{
		return sprintf(self::STAMP_MASK, $id, $time);
	}
	
	/**
	 * Build the conclusion string
	 */	
	protected function renderConclusion( $global_count=0, $passed_count=0, $failed_count=0 )
	{
		$info='';
		$overview='';
		$color = $this->warning_color;
		$completed_count = $passed_count+$failed_count;
		if (0===$global_count)
		{
			$info = "No test executed";
		}
		else
		{
			$info = 
				sprintf(true===$this->html ? self::STRONG_MASK_HTML : self::STRONG_MASK, $completed_count.'/'.$global_count)
				.' ('.$completed_count
				.($completed_count==1 ? " test" : " tests")
				.' complete on '.$global_count.' executed)'
				.' : ';
			$overview .= $global_count
				.($completed_count==1 ? " test" : " tests")
				.' : ';

			if (0===$passed_count)
			{
				$info .= "no success";
			} else {
				$color = $this->success_color;
				$info .= 
					sprintf(true===$this->html ? self::STRONG_MASK_HTML : self::STRONG_MASK, $passed_count)
					.($passed_count==1 ? " success" : " successes");
				$overview .= $passed_count.' OK';
			}
	
			$info .= ", ";
			if (0===$failed_count)
			{
				$info .= "no failure";
			} else {
				$color = $this->failure_color;
				$info .= 
					sprintf(true===$this->html ? self::STRONG_MASK_HTML : self::STRONG_MASK, $failed_count)
					.($failed_count==1 ? " failure" : " failures");
				$overview .= (strlen($overview) ? ' | ' : '').$failed_count.' KO';
			}
	
			$info .= " and ";
			if ($completed_count===$global_count)
			{
				$info .= "no error";
			} else {
				$color = $this->warning_color;
				$warning_count = $global_count-$completed_count;
				$info .= 
					sprintf(true===$this->html ? self::STRONG_MASK_HTML : self::STRONG_MASK, $warning_count)
					.($warning_count==1 ? " error" : " errors");
				$overview .= (strlen($overview) ? ' | ' : '').$warning_count.' warnings';
			}

			$info .= sprintf(true===$this->html ? self::OVERVIEW_MASK_HTML : self::OVERVIEW_MASK, $overview);
		}

		return sprintf(
			true===$this->html ? self::RESULT_MASK_HTML : self::RESULT_MASK, $color, $info
		);
	}
	
	/**
	 * Build the title group information string
	 */	
	protected function renderGroupTitle( $info )
	{
		return sprintf( 
			true===$this->html ? self::TITLE_MASK_HTML : self::TITLE_MASK, $info
		);
	}
	
	/**
	 * Build a simple text info block
	 */	
	protected function renderPresentationInfo( $info )
	{
		return sprintf( 
			true===$this->html ? self::BLOCKINFO_MASK_HTML : self::BLOCKINFO_MASK, $info
		);
	}

	/**
	 * Build one test case information item
	 */	
	protected function renderTestCaseItem( $info )
	{
		return sprintf( 
			true===$this->html ? self::TEST_ITEM_MASK_HTML : self::TEST_ITEM_MASK, $info
		);
	}
	
	/**
	 * Build the result information string
	 */	
	protected function renderResultInfo( $result_str, $title='' )
	{
		return sprintf( 
			true===$this->html ? self::RESULT_ITEM_MASK_HTML : self::RESULT_ITEM_MASK, $title, $result_str
		);
	}
	
	/**
	 * Build the method name information string
	 */	
	protected function renderMethodInfo( $method, $presentation=null )
	{
		return sprintf( 
			true===$this->html ? self::METHOD_MASK_HTML : self::METHOD_MASK, $method
		);
	}
	
	/**
	 * Build the method name information string
	 */	
	protected function renderMethodDescriptionInfo( $method )
	{
		return sprintf( 
			true===$this->html ? self::METHOD_DESCRIPTION_MASK_HTML : self::METHOD_DESCRIPTION_MASK, $method
		);
	}
	
	/**
	 * Build the OK validation information string
	 */	
	protected function renderPassedInfo()
	{
		return true===$this->html ? self::PASSED_INFO_HTML : self::PASSED_INFO;
	}
	
	/**
	 * Build the KO validation information string
	 */	
	protected function renderFailedInfo()
	{
		return true===$this->html ? self::FAILED_INFO_HTML : self::FAILED_INFO;
	}

	/**
	 * Format a date with micro-seconds
	 */
	protected static function microDateTime( $timestamp )
	{
		list($microsec, $date) = explode(" ", $timestamp);
		return date('Y-m-d H:i:', $date) . (date('s', $date) + $microsec);
	}

}

// Endfile