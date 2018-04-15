{if !isset($content_only) || !$content_only}
            </div><!-- #center_column -->
            {if isset($left_column_size) && !empty($left_column_size) && $page_name !='pagenotfound'}
              <div id="left_column" class="column col-xs-12 col-sm-{$left_column_size|intval}">{$HOOK_LEFT_COLUMN}</div>
            {/if}
            </div><!--.large-left-->
          </div><!--.row-->
          {if isset($right_column_size) && !empty($right_column_size) && $page_name !='pagenotfound'}
            <div id="right_column" class="col-xs-12 col-sm-{$right_column_size|intval} column">{$HOOK_RIGHT_COLUMN}</div>
          {/if}
          </div><!-- .row -->
        </div><!-- #columns -->
      </div><!-- .columns-container -->
      <!-- Footer -->
      {if $page_name !='pagenotfound'}
        <div class="footer-container">
          {assign var='displayMegaFooter' value={hook h='tmMegaLayoutFooter'}}
          {if isset($HOOK_FOOTER) || $displayMegaFooter}
            {if $displayMegaFooter}
              <footer id="footer">
                {$displayMegaFooter}
              </footer>
            {else}
              <footer id="footer" class="container">
                <div class="row">{$HOOK_FOOTER}</div>
              </footer>
            {/if}
          {/if}
        </div>
      {/if}
      <!-- #footer -->
    </div><!-- #page -->
  {/if}

  {include file="$tpl_dir./global.tpl"}
  </body>
</html>