class AuctionsChangePriceToInteger < ActiveRecord::Migration
  def up
    change_column :auctions, :price, :integer
  end

  def down
    change_column :auctions, :price, :decimal
  end
end
