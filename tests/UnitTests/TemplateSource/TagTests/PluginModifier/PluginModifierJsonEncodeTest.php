<?php
/**
 * Smarty PHPunit tests of modifier.
 * This file must be saved in UTF-8 encoding!
 */

namespace UnitTests\TemplateSource\TagTests\PluginModifier;
use PHPUnit_Smarty;

class PluginModifierJsonEncodeTest extends PHPUnit_Smarty
{
	public function setUp(): void
	{
		$this->setUpSmarty(__DIR__);
	}

	/**
	 * @dataProvider dataForDefault
	 */
	public function testDefault($value, $expected)
	{
		$tpl = $this->smarty->createTemplate('string:{$v|json_encode}');
		$tpl->assign("v", $value);
		$this->assertEquals($expected, $this->smarty->fetch($tpl));
	}

	/**
	 * @dataProvider dataForDefault
	 */
	public function testDefaultAsFunction($value, $expected)
	{
		$tpl = $this->smarty->createTemplate('string:{json_encode($v)}');
		$tpl->assign("v", $value);
		$this->assertEquals($expected, $this->smarty->fetch($tpl));
	}

	public function dataForDefault() {
		return [
			["abc", '"abc"'],
			[["abc"], '["abc"]'],
			[["abc",["a"=>2]], '["abc",{"a":2}]'],
			[["€uro",["Schlüssel"=>"Straße"]], '["\u20acuro",{"Schl\u00fcssel":"Stra\u00dfe"}]'],	# \u{20ac} = € = euro, \u{00fc} = ü = uuml, \u{00df} = ß = szlig
		];
	}

	/**
	 * @dataProvider dataForForceObject
	 */
	public function testForceObject($value, $expected)
	{
		$tpl = $this->smarty->createTemplate('string:{$v|json_encode:16}');
		$tpl->assign("v", $value);
		$this->assertEquals($expected, $this->smarty->fetch($tpl));
	}

	/**
	 * @dataProvider dataForForceObject
	 */
	public function testForceObjectAsFunction($value, $expected)
	{
		$tpl = $this->smarty->createTemplate('string:{json_encode($v,16)}');
		$tpl->assign("v", $value);
		$this->assertEquals($expected, $this->smarty->fetch($tpl));
	}

	public function dataForForceObject() {
		return [
			["abc", '"abc"'],
			[["abc"], '{"0":"abc"}'],
			[["abc",["a"=>2]], '{"0":"abc","1":{"a":2}}'],
			[["€uro"], '{"0":"\u20acuro"}'],
		];
	}

}
