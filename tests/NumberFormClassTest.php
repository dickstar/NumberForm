<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;

/**
 * @covers $number->chinese
 */
final class NumberToChineseTest extends TestCase
{
	/**
     * @dataProvider additionProvider
     */
    public function testShouldBeEqualToNumber($expected, $input) {
		$number = new NumberForm();
		$this->assertEquals($expected, $number->chinese($input));		
    }			
	
	/**
     * @dataProvider additionProvider
     */
	public function testShouldBeEqualToNumberRecursive($expected, $input) {
		$number = new NumberForm();
		$this->assertEquals($expected, $number->chinese_recursive($input));        
    }	
	
	public function additionProvider()
    {
        return [
            ['零', 0],
            ['一', 1],
			['十', 10],
            ['十一', 11],
			['一百', 100],
			['兩百三十四', 234],
			['一千', 1000],
			['一千零六', 1006],
			['一千零三十四', 1034],
			['一千兩百三十四', 1234],
			['一千四百', 1400],
			['一千四百零五', 1405],
			['兩千兩百三十四', 2234],
			['一萬', 10000],
			['一萬零三百零五', 10305],
			['一萬兩千三百四十五', 12345],
			['兩萬兩千兩百二十二', 22222],
			['三萬八千兩百一十九', 38219],			
			['十萬', 100000],			
			['十萬零六', 100006],
			['十萬零六十', 100060],
			['十萬三千四百零六', 103406],
			['十萬三千四百五十六', 103456],
			['十一萬', 110000],
			['十二萬', 120000],
			['十二萬三千四百五十六', 123456],
			['二十萬零二', 200002],				
			['二十萬零兩百零二', 200202],	
			['二十一萬', 210000],	
			['二十二萬兩千兩百二十二', 222222],	
			['二十二萬三千四百五十六', 223456],			
			['四十二萬', 420000],
			['八十萬零六百零五', 800605],
			['八十七萬零六百零五', 870605],
			['九十萬', 900000],			
			['九十萬八千零六十', 908060],
			['九十七萬八千零六', 978006],	
			['一百萬', 1000000]
        ];
    }
	
	/**
     * @dataProvider			execptionProvider
	 * @expectedException		InvalidArgumentException     
     */
	public function testInvalidArgumentExceptions($input) {
		$number = new NumberForm();
		$number->chinese($input);		
    }	
	
	/**
     * @dataProvider			execptionProvider
	 * @expectedException		InvalidArgumentException     
     */
	public function testRecursiveInvalidArgumentExceptions($input) {
		$number = new NumberForm();
		$number->chinese_recursive($input);		
    }	
	
	public function execptionProvider() {
		return [
			[1000001],
			[-1],			
			["零"],
			['a']
        ];
    }
	
}
