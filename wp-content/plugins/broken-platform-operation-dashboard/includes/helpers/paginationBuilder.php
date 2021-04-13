<?php


namespace Samadhan {


    class PaginationBuilder
    {

        protected int $pageSize;
        protected int $currentPage;
        protected  int $totalRecords;
        protected string $totalColumns;
        protected int $buttonColumnSpan;


        function __construct( $pageSize, $currentPage, $totalRecords, $totalTableColumns)
        {
            $this->pageSize = $pageSize;
            $this->currentPage = $currentPage;
            $this->totalRecords = $totalRecords;
            $this->totalColumns = $totalTableColumns;

            $this->buttonColumnSpan = 6;
        }


        public function GetPaginationRow()
        {
            $firstItemNo = ($this->currentPage -1) * $this->pageSize + 1;
            $lastItemNo = $firstItemNo + $this->pageSize;
            $colSpan = $this->totalColumns - $this->buttonColumnSpan;

            $footer ='<tfoot class="smdn-pagination-footer"><tr><td colspan="' . $colSpan. '"> Showing ' .$firstItemNo. ' to ' . $lastItemNo . ' of ' . $this->totalRecords. ' entries</td>';
            $footer .= $this->getNaviationButtons();
            $footer .='</tr></tfoot>';
            return $footer;
        }

        protected function getNaviationButtons(){

            $buttons ='<td class="button-cells" colspan="' . $this->buttonColumnSpan . '">';
            //$buttons .='<form method="post">';
            $buttons .='<input type="button" class="pagination-button" name="prevButton" value="|<" onclick="JavaScript:FirstPageClicked();">';
            $buttons .='<input type="button" class="pagination-button" name="prevButton" value="<<" onclick="JavaScript:PrevButtonClicked();">';
            $buttons .='<input type="button" class="pagination-button" name="nextButton" value=">>" onclick="JavaScript:NextButtonClicked();">';
            //$buttons .='</form>';
            $buttons .='</td>';
            return $buttons;
        }


    }
}
