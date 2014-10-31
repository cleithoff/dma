<?xml version="1.0" encoding="utf-8"?>

<xsl:stylesheet version="1.0" xmlns:fo="http://www.w3.org/1999/XSL/Format" xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns:j4lext="xalan://com.java4less.xreport.fop.XLSTExtension" extension-element-prefixes="j4lext j4luserext" xmlns:j4luserext="xalan://com.java4less.xreport.fop.XLSTDummyExtension"  >

<!-- Page layout information -->
<xsl:template match="/">
<fo:root xmlns:fo="http://www.w3.org/1999/XSL/Format">
<fo:layout-master-set>
<fo:simple-page-master master-name="main" page-height="450mm" page-width="320mm" font-family="Frutiger" font-weight="300" margin-left="4mm" margin-top="15mm" margin-right="4mm" margin-bottom="15mm">
<fo:region-body column-count="2" column-gap="60mm" margin="0"/>
<fo:region-before />
</fo:simple-page-master>
</fo:layout-master-set>

<fo:page-sequence master-reference="main">
<fo:flow flow-name="xsl-region-body">
<fo:block>
      	<xsl:apply-templates select="data/ReportDetail/ListOfReportDetail/ItemOfReportDetail" />
</fo:block>
</fo:flow>

</fo:page-sequence>

</fo:root>

</xsl:template>

<xsl:template match="data/ReportDetail/ListOfReportDetail/ItemOfReportDetail">

    <fo:block-container margin="6mm" width="103mm" height="72mm">
	  <fo:block absolute-position="absolute" left="0mm" top="0mm">
		<fo:instream-foreign-object>
              <svg xmlns="http://www.w3.org/2000/svg" width="102mm" height="72mm">
       			<line x1="0mm" x2="6mm" y1="9mm" y2="9mm" style="fill:#000000;stroke:#000000;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1" />
       			<line x1="9mm" x2="9mm" y1="0mm" y2="6mm" style="fill:#000000;stroke:#000000;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1" />
       			
       			<line x1="0mm" x2="6mm" y1="63mm" y2="63mm" style="fill:#000000;stroke:#000000;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1" />
       			<line x1="9mm" x2="9mm" y1="66mm" y2="72mm" style="fill:#000000;stroke:#000000;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1" />
       			
       			<line x1="97mm" x2="103mm" y1="9mm" y2="9mm" style="fill:#000000;stroke:#000000;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1" />
       			<line x1="94mm" x2="94mm" y1="0mm" y2="6mm" style="fill:#000000;stroke:#000000;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1" />
       			
       			<line x1="97mm" x2="103mm" y1="63mm" y2="63mm" style="fill:#000000;stroke:#000000;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1" />
       			<line x1="94mm" x2="94mm" y1="66mm" y2="72mm" style="fill:#000000;stroke:#000000;stroke-width:1px;stroke-linecap:butt;stroke-linejoin:miter;stroke-opacity:1" />
              </svg>
            </fo:instream-foreign-object>
	  </fo:block>
      <fo:block absolute-position="absolute" left="30mm" top="21mm">
      	<fo:instream-foreign-object  >
			<j4lbarcode xmlns="http://java4less.com/j4lbarcode/fop" mode="inline"><Barcode1D><VALUE><xsl:value-of select="barcode"></xsl:value-of></VALUE><X>1</X><BARHEIGHT>20</BARHEIGHT><LEFTMARGIN>10</LEFTMARGIN><SET>A</SET><TYPE>INTERLEAVED25</TYPE><N>2</N><TOPMARGIN>25</TOPMARGIN><CHECKSUM>true</CHECKSUM></Barcode1D></j4lbarcode>
		</fo:instream-foreign-object>
	  </fo:block>
	</fo:block-container>
</xsl:template>

</xsl:stylesheet>