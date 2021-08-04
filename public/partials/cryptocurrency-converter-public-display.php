<?php
/**
 * Description document
 *
 * @file
 * @package package
 */

use Carbon\Carbon;

?>
<div class="cryptocurrency_converter">
	<?php
	/** @var array $converter_currency_data */
	if ( is_array( $converter_currency_data ) and count( $converter_currency_data ) > 1 ) : ?>
        <div class="convert-component">
            <input type="hidden" class="rate-value-left"
                   data-currency="<?php echo $converter_currency_data[0]->cur_symbol ?>" id="left-rate"
                   value="<?php echo $converter_currency_data[0]->usd ?>">
            <input type="hidden" class="rate-value-right"
                   data-currency="<?php echo $converter_currency_data[1]->cur_symbol ?>" id="right-rate"
                   value="<?php echo $converter_currency_data[1]->usd ?>">
            <div class="convert-component__form">
                <div class="convert-component__input-group">
                    <div class="convert-component__form-group convert-component__form-group--left">
                        <div class="convert-component__input-box">
                            <input class="convert-component__form-control" type="text" name="amount" placeholder="1"
                                   value="1">
                        </div>
                        <div class="convert-component__dropdown-box">
                            <div class="convert-component__dropdown">
                                <select class="left-list" sort="right-list">
									<?php foreach ( $converter_currency_data as $currency ): ?>
                                        <option value="<?php echo $currency->cur_symbol ?>"><?php echo $currency->cur_symbol ?>
                                            <span>- <?php echo $currency->cur_name ?></option>
									<?php endforeach; ?>
                                </select>

                            </div>
                        </div>
                    </div>
                    <div class="convert-component__direction-switch js-ConvertDirectionSwitch">
                    <span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="22" viewBox="0 0 16 22">
                            <path d="M0 6.875v-3.75h10.82V0L16 5l-5.18 5V6.875H0zM16 18.875v-3.75H5.18V12L0 17l5.18 5v-3.125H16z"></path>
                        </svg>
                    </span>
                    </div>
                    <div class="convert-component__form-group convert-component__form-group--right">
                        <div class="convert-component__input-box">
                            <input class="convert-component__form-control" type="text"
                                   name="convert-result" placeholder="1" value="">
                        </div>
                        <div class="convert-component__dropdown-box">
                            <div class="convert-component__dropdown">
                                <select class="right-list" sort="right-list">
									<?php foreach ( $converter_currency_data as $k => $currency ): ?>
                                        <option value="<?php echo $currency->cur_symbol ?>" <?php echo ( $k == 1 ) ? 'selected' : '' ?>><?php echo $currency->cur_symbol ?>
                                            <span>- <?php echo $currency->cur_name ?></option>
									<?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <span class="convert-component__button js-ConvertCurrency"><?php _e( 'Convert', CCC_PLUGIN_SLUG ) ?></span>
            </div>
            <div class="no-select2-error">
                <div class="no-select2-error__message"><?php _e( 'This plugin is required Select2 library enabled ', CCC_PLUGIN_SLUG ) ?></div>
            </div>
        </div>
	<?php endif; ?>
    <div class="recently-converted">
        <h4 class="recently-converted__title">
			<?php _e( 'Recently converted', CCC_PLUGIN_SLUG ) ?>
        </h4>
        <div class="recently-converted__data">
			<?php
			/** @var array $converter_log_data */
			if ( is_array( $converter_log_data ) ) : ?>
                <ul class="recently-converted__column">
					<?php foreach ( $converter_log_data as $log_data ): ?>
                        <li>
                            <div class="recently-converted__convert-description">
								<?= $log_data->convert_value ?> <?= $log_data->convert_from ?>
                                to <?= $log_data->convert_to ?>
                            </div>
                            <div class="recently-converted__dots"></div>
                            <div class="recently-converted__convert-time"><?= Carbon::parse( $log_data->time_action )->diffForHumans(); ?></div>
                        </li>
					<?php endforeach; ?>
                </ul>
			<?php endif; ?>
        </div>
    </div>
</div>
