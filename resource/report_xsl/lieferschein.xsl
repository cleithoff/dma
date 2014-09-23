<?xml version="1.0" encoding="utf-8"?>

<xsl:stylesheet version="1.0" xmlns:fo="http://www.w3.org/1999/XSL/Format" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

<!-- Page layout information -->
<xsl:template match="/">
<fo:root xmlns:fo="http://www.w3.org/1999/XSL/Format">

<fo:layout-master-set>
<fo:simple-page-master master-name="main" page-height="5.51cm" page-width="11.02cm" font-family="sans-serif" margin="0.5cm" margin-bottom="1.8cm">
<fo:region-body />
<fo:region-before />
</fo:simple-page-master>
</fo:layout-master-set>

<fo:page-sequence master-reference="main">
<fo:flow flow-name="xsl-region-body">
      <fo:block>
        <fo:footnote>
          <fo:inline/>
          <fo:footnote-body>
            <fo:block>
            </fo:block>
          </fo:footnote-body>
        </fo:footnote>
      </fo:block>
</fo:flow>

</fo:page-sequence>

</fo:root>

</xsl:template>


</xsl:stylesheet>